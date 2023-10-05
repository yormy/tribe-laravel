<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeMembership;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Observers\Events\ProjectMemberInviteAcceptedEvent;
use Yormy\TribeLaravel\Observers\Events\ProjectMemberInvitedEvent;
use Yormy\TribeLaravel\Observers\Events\ProjectMemberRemovedEvent;

class TribeMembershipRepository
{
//    public function __construct(private ?TribeMembership $model = null)
//    {
//        if (! $model) {
//            $this->model = new TribeMembership();
//        }
//    }
//
//    public function isMember(Project $project, $member): bool
//    {
//        $x = $this->model
//            ->active()
//            ->project($project)
//            ->member($member)
//            ->get()
//            ->count();
////
////        dd($x);
//
//        return (bool)$member;
//    }
}
