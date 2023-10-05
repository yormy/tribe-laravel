<?php declare(strict_types=1);

namespace Yormy\TribeLaravel\Tests\Unit\Traits;

use Illuminate\Contracts\Validation\ValidationRule;

trait AssertRuleTrait
{
    protected function assertRulePasses(ValidationRule $rule, $value, string $attribute = '')
    {
        $passes = $this->getRuleResult($rule, $value, $attribute);

        $this->assertTrue($passes);
    }

    protected function assertRuleFails(ValidationRule $rule, $value, string $attribute = '')
    {
        $passes = $this->getRuleResult($rule, $value, $attribute);

        $this->assertFalse($passes);
    }

    private function getRuleResult(ValidationRule $rule, $value, string $attribute = ''): bool
    {
        $passes = true;
        $rule->validate($attribute, $value, static function () use (&$passes): void {
            $passes = false;
        });

        return $passes;
    }
}
