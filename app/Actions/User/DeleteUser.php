<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteUser
{
    public function handle(User $user): void
    {
        $user->delete();

        if ($user->avatar_path !== null) {
            Storage::disk('public')->delete($user->avatar_path);
        }
    }
}
