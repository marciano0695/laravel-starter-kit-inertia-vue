<?php

declare(strict_types=1);

use App\Actions\User\DeleteUser;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('may delete a user', function (): void {
    $user = User::factory()->create();

    $action = resolve(DeleteUser::class);

    $action->handle($user);

    expect($user->exists)->toBeFalse();
});

it('deletes the avatar file with the user', function (): void {
    Storage::fake('public');

    $user = User::factory()->create([
        'avatar_path' => UploadedFile::fake()->image('avatar.png')->store('avatars', 'public'),
    ]);
    $path = (string) $user->avatar_path;

    $action = resolve(DeleteUser::class);

    $action->handle($user);

    expect($user->exists)->toBeFalse();
    Storage::disk('public')->assertMissing($path);
});
