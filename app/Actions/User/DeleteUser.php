<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

final readonly class DeleteUser
{
    public function handle(User $user): void
    {
        $user->delete();
    }
}
