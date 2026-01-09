<?php

declare(strict_types=1);

namespace App\Actions\User;

use Illuminate\Support\Facades\Password;

final readonly class CreateUserPasswordResetNotification
{
    /**
     * @param  array<string, mixed>  $credentials
     */
    public function handle(array $credentials): string
    {
        return Password::sendResetLink($credentials);
    }
}
