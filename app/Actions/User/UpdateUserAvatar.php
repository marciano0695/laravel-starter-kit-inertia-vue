<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final readonly class UpdateUserAvatar
{
    public function handle(User $user, UploadedFile $avatar): void
    {
        $previousPath = $user->avatar_path;

        $path = $avatar->store('avatars', 'public');

        throw_if($path === false, RuntimeException::class, 'Unable to store the avatar.');

        $user->update([
            'avatar_path' => $path,
        ]);

        // Drop the old file only once the new one is saved, so a failure never leaves the user without a picture.
        if ($previousPath !== null) {
            Storage::disk('public')->delete($previousPath);
        }
    }
}
