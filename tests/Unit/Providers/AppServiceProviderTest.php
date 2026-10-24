<?php

declare(strict_types=1);

use Illuminate\Validation\Rules\Password;

it('keeps password rules relaxed outside production', function (): void {
    expect(Password::defaults()->appliedRules())->toMatchArray([
        'min' => 12,
        'max' => 255,
        'mixedCase' => false,
        'numbers' => false,
        'symbols' => false,
        'uncompromised' => false,
    ]);
});

it('requires strong passwords in production', function (): void {
    app()['env'] = 'production';

    expect(Password::defaults()->appliedRules())->toMatchArray([
        'min' => 12,
        'max' => 255,
        'mixedCase' => true,
        'numbers' => true,
        'symbols' => true,
        'uncompromised' => true,
    ]);
});
