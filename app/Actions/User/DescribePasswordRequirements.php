<?php

declare(strict_types=1);

namespace App\Actions\User;

use Illuminate\Validation\Rules\Password;

final readonly class DescribePasswordRequirements
{
    /**
     * Read from Password::defaults() so the hints always match what validation enforces.
     *
     * @return array{rules: string, hints: list<string>}
     */
    public function handle(): array
    {
        $password = Password::defaults();

        /** @var array{min: int, mixedCase: bool, letters: bool, numbers: bool, symbols: bool, uncompromised: bool} $applied */
        $applied = $password->appliedRules();

        $hints = [__('At least :count characters', ['count' => $applied['min']])];

        // Mixed case already implies letters, so only one of the two is worth showing.
        if ($applied['mixedCase']) {
            $hints[] = __('Upper and lowercase letters');
        } elseif ($applied['letters']) {
            $hints[] = __('At least one letter');
        }

        if ($applied['numbers']) {
            $hints[] = __('At least one number');
        }

        if ($applied['symbols']) {
            $hints[] = __('At least one symbol');
        }

        if ($applied['uncompromised']) {
            $hints[] = __('Not found in a known data leak');
        }

        return [
            'rules' => $password->toPasswordRulesString(),
            'hints' => $hints,
        ];
    }
}
