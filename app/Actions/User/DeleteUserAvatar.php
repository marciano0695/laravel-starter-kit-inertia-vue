<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteUserAvatar
{
    public function handle(User $user): void
    {
        $path = $user->avatar_path;

        if ($path === null) {
            return;
        }

        $user->update([
            'avatar_path' => null,
        ]);

        Storage::disk('public')->delete($path);
    }
}
