<?php

declare(strict_types=1);

use App\Actions\User\UpdateUserAvatar;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores the avatar on the public disk', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $action = resolve(UpdateUserAvatar::class);

    $action->handle($user, UploadedFile::fake()->image('avatar.png'));

    expect($user->refresh()->avatar_path)->toStartWith('avatars/');
    Storage::disk('public')->assertExists((string) $user->avatar_path);
});

it('deletes the previous avatar when replacing it', function (): void {
    Storage::fake('public');

    $user = User::factory()->create([
        'avatar_path' => UploadedFile::fake()->image('old.png')->store('avatars', 'public'),
    ]);
    $previousPath = (string) $user->avatar_path;

    $action = resolve(UpdateUserAvatar::class);

    $action->handle($user, UploadedFile::fake()->image('new.png'));

    expect($user->refresh()->avatar_path)->not->toBe($previousPath);
    Storage::disk('public')->assertMissing($previousPath);
    Storage::disk('public')->assertExists((string) $user->avatar_path);
});

it('keeps the current avatar when the new one cannot be stored', function (): void {
    $disk = Mockery::mock(Filesystem::class);
    $disk->shouldReceive('putFileAs')->andReturn(false);
    Storage::shouldReceive('disk')->with('public')->andReturn($disk);

    $user = User::factory()->create(['avatar_path' => 'avatars/current.png']);

    $action = resolve(UpdateUserAvatar::class);

    expect(fn () => $action->handle($user, UploadedFile::fake()->image('avatar.png')))
        ->toThrow(RuntimeException::class, 'Unable to store the avatar.');

    expect($user->refresh()->avatar_path)->toBe('avatars/current.png');
});
