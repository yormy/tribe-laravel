<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Yormy\Xid\Observers\Events\XidInvalidEvent;
use Yormy\Xid\Services\XidService;

class DummyRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ("fail" === $value) {
            $fail('xid.message.invalid');
        }
    }

//    public function validate(string $attribute, mixed $value, Closure $fail): void
//    {
//        $isValid = XidService::validate($value);
//
//        if (! $isValid) {
//            event(new XidInvalidEvent($value));
//
//            $fail('xid.message.invalid');
//        }
//    }
}
