<?php

declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Services;

use Illuminate\Database\Eloquent\Model;
use Yormy\ProjectMembersLaravel\Exceptions\InvalidInviteTokenException;
use Yormy\ProjectMembersLaravel\Models\ProjectInvite;
use Yormy\ProjectMembersLaravel\Models\ProjectMember;
use Yormy\ProjectMembersLaravel\Observers\Events\MemberAcceptedInviteEvent;
use Yormy\ProjectMembersLaravel\Observers\Events\MemberDeniedInviteEvent;
use Yormy\ProjectMembersLaravel\Observers\Events\MemberInvitedEvent;
use Yormy\ProjectMembersLaravel\Observers\Events\MemberLeftProjectEvent;

class ProjectService
{
    public function getSettableRoles()
    {
        $roleNames = config('project-members-laravel.role_names');
        unset($roleNames[config('project-members-laravel.role_owner')]);

        return $roleNames;
    }

    //    /**
    //     * Creating an encrypted project api token.
    //     * This allows us to validate the token (decrypt it) without database access
    //     * Before validating the token.
    //     */
    //    public static function generateProjectApiKey(string $key)
    //    {
    //        $encryptionKey = base64_decode(str_replace('base64:','', $key));
    //        $token = Str::random(40);
    //        $encrypter = new Encrypter($encryptionKey, 'AES-256-CBC');
    //        return $encrypter->encryptString($token);
    //    }
    //
    //    public static function validateProjectApiKey(string $key, $payload): bool
    //    {
    //        $encryptionKey = base64_decode(str_replace('base64:','', $key));
    //        $encrypter = new Encrypter($encryptionKey, 'AES-256-CBC');
    //        try {
    //            $encrypter->decryptString($payload);
    //            return true;
    //        } catch (\Exception $e) {
    //            return false;
    //        }
    //    }

    public function updateMembership($project, $member, array $data): void
    {
        $membership = $this->getMembership($project, $member);
        if ($membership) {
            $membership->update($data);
        }

        MemberLeftProjectEvent::dispatch($project, $member);
    }

    public function getMembership($project, $member): ?ProjectMember
    {
        return ProjectMember::where('project_id', $project->id)
            ->where('user_id', $member->id)
            ->first();
    }

    public function leaveProject($project, $member): void
    {
        $membership = $this->getMembership($project, $member);
        if ($membership) {
            $membership->delete();
        }

        MemberLeftProjectEvent::dispatch($project, $member);
    }

    public function inviteToProject(Model $project, Model $member, Model $invitedBy): ProjectInvite
    {
        $invite = ProjectInvite::create([
            'project_id' => $project->id,
            'user_id' => $member->id,
            'email' => $member->email,
            'invited_by' => $invitedBy->id,
        ]);

        MemberInvitedEvent::dispatch($project, $member);

        return $invite;
    }

    public function hasPendingInvite(Model $project, Model $member): bool
    {
        return ProjectInvite::where('project_id', $project->id)
            ->where('user_id', $member->id)
            ->first() !== null;
    }

    public function receivedInvites(Model $member)
    {
        return ProjectInvite::where([
            'user_id' => $member->id,
        ])->get();
    }

    public function inviteDestroy(Model $project, string $denyToken)
    {
        $invite = ProjectInvite::where([
            'project_id' => $project->id,
            'deny_token' => $denyToken,
        ])->first();

        if (! $invite) {
            throw new InvalidInviteTokenException('invalid deny token');
        }

        $invite->delete();

        return $invite;
    }

    public function inviteAccept(Model $member, string $acceptToken): ProjectInvite
    {
        $inviteReceived = ProjectInvite::where([
            'user_id' => $member->id,
            'accept_token' => $acceptToken,
        ])->first();

        if (! $inviteReceived) {
            throw new InvalidInviteTokenException('invalid accept token');
        }

        MemberAcceptedInviteEvent::dispatch($inviteReceived->project, $member);

        $this->addMember($inviteReceived->project_id, $inviteReceived->user_id);

        $inviteReceived->delete();

        return $inviteReceived;
    }

    public function inviteDeny(Model $member, string $denyToken): ProjectInvite
    {
        $inviteReceived = ProjectInvite::where([
            'user_id' => $member->id,
            'deny_token' => $denyToken,
        ])->first();

        if ($denyToken && ! $inviteReceived) {
            throw new InvalidInviteTokenException('invalid deny token');
        }

        MemberDeniedInviteEvent::dispatch($inviteReceived->project, $member);

        $inviteReceived->delete();

        return $inviteReceived;
    }

    private function addMember($projectId, $memberId): void
    {
        ProjectMember::create([
            'project_id' => $projectId,
            'user_id' => $memberId,
        ]);
    }
}
