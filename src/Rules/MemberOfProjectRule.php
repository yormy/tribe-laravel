<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\Xid\Observers\Events\XidInvalidEvent;
use Yormy\Xid\Services\XidService;

class MemberOfProjectRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $projectXid = $value;

        $member = Auth::user();
        if (!$member) {
            $fail('xid.message.invalid');
            return;
        }

        $projectRepository = new ProjectRepository();
        $project = $projectRepository->findOneByXid($projectXid);
        if (!$project) {
            $fail('xid.message.invalid');
            return;
        }

        $isMember = $projectRepository->isMember($project, $member);
        if (!$isMember) {
            $fail('xid.message.invalid');
            return;
        }
    }
}
