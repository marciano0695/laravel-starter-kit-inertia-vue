<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\DeleteUserAvatar;
use App\Actions\User\UpdateUserAvatar;
use App\Http\Requests\UpdateUserAvatarRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;

final readonly class UserAvatarController
{
    public function update(UpdateUserAvatarRequest $request, #[CurrentUser] User $user, UpdateUserAvatar $action): RedirectResponse
    {
        $avatar = $request->file('avatar');
        assert($avatar instanceof UploadedFile);

        $action->handle($user, $avatar);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Profile picture updated.'),
        ]);

        return to_route('user-profile.edit');
    }

    public function destroy(#[CurrentUser] User $user, DeleteUserAvatar $action): RedirectResponse
    {
        $action->handle($user);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Profile picture removed.'),
        ]);

        return to_route('user-profile.edit');
    }
}
