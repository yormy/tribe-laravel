<?php

namespace Yormy\Xid\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Yormy\Xid\Observers\Events\XidInvalidEvent;
use Yormy\Xid\Services\XidService;

class ValidXidRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isValid = XidService::validate($value);

        if (! $isValid) {
            event(new XidInvalidEvent($value));

            $fail('xid.message.invalid');
        }
    }
}
