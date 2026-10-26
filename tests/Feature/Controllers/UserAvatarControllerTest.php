<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('may update avatar', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('user-profile.edit')
        ->put(route('user-avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.png'),
        ]);

    $response->assertRedirectToRoute('user-profile.edit')
        ->assertSessionDoesntHaveErrors()
        ->assertInertiaFlash('toast', ['type' => 'success', 'message' => __('Profile picture updated.')]);

    Storage::disk('public')->assertExists($user->refresh()->avatar_path);
});

it('requires avatar to update', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('user-profile.edit')
        ->put(route('user-avatar.update'));

    $response->assertRedirectToRoute('user-profile.edit')
        ->assertSessionHasErrors(['avatar' => 'The profile picture field is required.']);
});

it('rejects avatars that are not a web image', function (UploadedFile $avatar): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('user-profile.edit')
        ->put(route('user-avatar.update'), [
            'avatar' => $avatar,
        ]);

    $response->assertRedirectToRoute('user-profile.edit')
        ->assertSessionHasErrors(['avatar' => 'The profile picture field must be a file of type: jpg, jpeg, png, gif, webp.']);

    expect($user->refresh()->avatar_path)->toBeNull();
})->with([
    'pdf' => fn (): UploadedFile => UploadedFile::fake()->create('avatar.pdf', 100, 'application/pdf'),
    'svg' => fn (): UploadedFile => UploadedFile::fake()->create('avatar.svg', 1, 'image/svg+xml'),
    'heic' => fn (): UploadedFile => UploadedFile::fake()->create('avatar.heic', 100, 'image/heic'),
    // A real file, so the type comes from its content rather than the name it was given.
    'text named as png' => function (): UploadedFile {
        $path = (string) tempnam(sys_get_temp_dir(), 'avatar');
        file_put_contents($path, 'not an image');

        return new UploadedFile($path, 'avatar.png', 'image/png', null, true);
    },
]);

it('rejects avatars larger than 2 MB', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('user-profile.edit')
        ->put(route('user-avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.png')->size(2049),
        ]);

    $response->assertRedirectToRoute('user-profile.edit')
        ->assertSessionHasErrors(['avatar' => 'The profile picture field must not be greater than 2048 kilobytes.']);

    expect($user->refresh()->avatar_path)->toBeNull();
});

it('may remove avatar', function (): void {
    Storage::fake('public');

    $user = User::factory()->create([
        'avatar_path' => UploadedFile::fake()->image('avatar.png')->store('avatars', 'public'),
    ]);
    $path = $user->avatar_path;

    $response = $this->actingAs($user)
        ->fromRoute('user-profile.edit')
        ->delete(route('user-avatar.destroy'));

    $response->assertRedirectToRoute('user-profile.edit')
        ->assertInertiaFlash('toast', ['type' => 'success', 'message' => __('Profile picture removed.')]);

    expect($user->refresh()->avatar_path)->toBeNull();
    Storage::disk('public')->assertMissing((string) $path);
});

it('requires authentication to update avatar', function (): void {
    $response = $this->put(route('user-avatar.update'), [
        'avatar' => UploadedFile::fake()->image('avatar.png'),
    ]);

    $response->assertRedirectToRoute('login');
});

it('requires authentication to remove avatar', function (): void {
    $response = $this->delete(route('user-avatar.destroy'));

    $response->assertRedirectToRoute('login');
});
