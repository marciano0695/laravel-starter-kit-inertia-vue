<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Fortify\Features;

it('offers passkey sign in on the login page', function (): void {
    $page = visit(route('login'));

    $page->assertSee('Sign in with a passkey')
        ->assertSee('Or continue with email')
        ->assertNoJavaScriptErrors();
});

it('offers passkey confirmation on the password confirmation page', function (): void {
    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    $page = visit(route('user-passkey.index'));

    $page->assertSee('Confirm with passkey')
        ->assertSee('Or confirm with password')
        ->assertNoJavaScriptErrors();
});

it('shows the passkeys settings page with an empty state', function (): void {
    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    $page = visit(route('user-passkey.index'));

    $page->fill('password', 'password')
        ->click('@confirm-password-button')
        ->assertSee('No passkeys yet')
        ->click('@add-passkey-button')
        ->assertSee('Passkey name')
        ->assertNoJavaScriptErrors();
});

it('lists registered passkeys on the settings page', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $user->passkeys()->create([
        'name' => 'MacBook Pro',
        'credential_id' => 'credential-id',
        'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
    ]);

    $this->actingAs($user);

    $page = visit(route('user-passkey.index'));

    $page->fill('password', 'password')
        ->click('@confirm-password-button')
        ->assertSee('MacBook Pro')
        ->assertNoJavaScriptErrors();
});

it('removes a passkey from the settings page', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $user->passkeys()->create([
        'name' => 'MacBook Pro',
        'credential_id' => 'credential-id',
        'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
    ]);

    $this->actingAs($user);

    $page = visit(route('user-passkey.index'));

    $page->fill('password', 'password')
        ->click('@confirm-password-button')
        ->click('@remove-passkey-button')
        ->click('@confirm-remove-passkey-button')
        ->assertSee('No passkeys yet')
        ->assertNoJavaScriptErrors();

    expect($user->passkeys()->count())->toBe(0);
});

it('hides passkeys everywhere when passkeys are disabled', function (): void {
    config(['fortify.features' => [Features::twoFactorAuthentication()]]);

    visit(route('login'))
        ->assertSee('Log in to your account')
        ->assertDontSee('Sign in with a passkey')
        ->assertNoJavaScriptErrors();

    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    visit(route('user-profile.edit'))
        ->assertSee('Two-Factor Auth')
        ->assertDontSee('Passkeys')
        ->assertNoJavaScriptErrors();
});
