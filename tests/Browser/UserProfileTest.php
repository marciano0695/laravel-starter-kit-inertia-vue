<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('lets an unverified user resend the verification email from the profile page', function (): void {
    $this->actingAs(User::factory()->unverified()->withoutTwoFactor()->create());

    $page = visit(route('user-profile.edit'));

    $page->assertSee('Your email address is unverified.')
        ->click('Click here to resend the verification email.')
        ->assertSee('A new verification link has been sent to your email address.')
        ->assertNoJavaScriptErrors();
});

it('does not show the verification notice to a verified user', function (): void {
    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    $page = visit(route('user-profile.edit'));

    $page->assertSee('Profile information')
        ->assertDontSee('Your email address is unverified.')
        ->assertNoJavaScriptErrors();
});

it('uploads and removes a profile picture from the profile page', function (): void {
    Storage::fake('public');

    $user = User::factory()->withoutTwoFactor()->create();
    $this->actingAs($user);

    // The browser test server replaces bytes that aren't valid UTF-8, so upload a 1x1 GIF built only from ASCII bytes.
    $avatar = sys_get_temp_dir().'/avatar.gif';
    file_put_contents($avatar, "GIF89a\x01\x00\x01\x00\x00\x00\x00,\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02D\x01\x00;");

    $page = visit(route('user-profile.edit'));

    $page->attach('#avatar', $avatar)
        ->assertSee('Profile picture updated.')
        ->assertPresent('@remove-avatar-button')
        ->assertNoJavaScriptErrors();

    Storage::disk('public')->assertExists((string) $user->refresh()->avatar_path);

    $page->click('@remove-avatar-button')
        ->assertSee('Profile picture removed.')
        ->assertMissing('@remove-avatar-button')
        ->assertNoJavaScriptErrors();

    expect($user->refresh()->avatar_path)->toBeNull();
});

it('shows why a profile picture was rejected', function (): void {
    Storage::fake('public');

    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    $document = UploadedFile::fake()->create('avatar.pdf', 10, 'application/pdf');

    visit(route('user-profile.edit'))
        ->attach('#avatar', $document->getPathname())
        ->assertSee('The profile picture field must be a file of type: jpg, jpeg, png, gif, webp.')
        ->assertMissing('@remove-avatar-button')
        ->assertNoJavaScriptErrors();
});
