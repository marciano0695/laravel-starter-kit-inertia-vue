<?php

declare(strict_types=1);

use App\Actions\User\DeleteUserAvatar;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('deletes the avatar file and clears the path', function (): void {
    Storage::fake('public');

    $user = User::factory()->create([
        'avatar_path' => UploadedFile::fake()->image('avatar.png')->store('avatars', 'public'),
    ]);
    $path = (string) $user->avatar_path;

    $action = resolve(DeleteUserAvatar::class);

    $action->handle($user);

    expect($user->refresh()->avatar_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

it('leaves a user without an avatar untouched', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $action = resolve(DeleteUserAvatar::class);

    $action->handle($user);

    expect($user->refresh()->avatar_path)->toBeNull();
});
