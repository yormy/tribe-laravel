<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Yormy\TribeLaravel\Repositories\ProjectRepository;

class ProjectPermissionRule implements ValidationRule
{
    public function __construct(private string $permission)
    {
        // ...
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $projectXid = $value;
        $projectRepository = new ProjectRepository();
        $project = $projectRepository->findOneActiveByXid($projectXid);
        if (! $project) {
            $fail('xid.message.invalid');
        }

        (new MemberOfProjectRule())->validate($attribute, $value, $fail);

        $member = Auth::user();
        if (! $member) {
            $fail('xid.message.invalid');

            return;
        }

        $memberHasPermission = $projectRepository->memberHasPermission($project, $member, $this->permission);
        if (! $memberHasPermission) {
            $fail('xid.message.invalid');

            return;
        }

    }
}
