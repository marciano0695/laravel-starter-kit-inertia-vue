<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Fortify\Features;

it('renders passkeys page', function (): void {
    $user = User::factory()->create();

    $passkey = $user->passkeys()->create([
        'name' => 'MacBook Pro',
        'credential_id' => 'credential-id',
        'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
    ]);

    $this->actingAs($user)->session(['auth.password_confirmed_at' => time()]);

    $response = $this->fromRoute('dashboard')
        ->get(route('user-passkey.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-passkey/index')
            ->has('passkeys', 1)
            ->where('passkeys.0.name', 'MacBook Pro')
            ->where('passkeys.0.destroy_url', route('passkey.destroy', $passkey))
            ->where('passkeys.0.created_at_diff', '0 seconds ago')
            ->where('passkeys.0.last_used_at_diff', null));
});

it('renders passkeys page without passkeys', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->session(['auth.password_confirmed_at' => time()]);

    $response = $this->fromRoute('dashboard')
        ->get(route('user-passkey.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-passkey/index')
            ->has('passkeys', 0));
});

it('lists the most recent passkey first', function (): void {
    $user = User::factory()->create();

    $user->passkeys()->create([
        'name' => 'Old Key',
        'credential_id' => 'old-credential-id',
        'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
        'created_at' => now()->subDay(),
    ]);

    $user->passkeys()->create([
        'name' => 'New Key',
        'credential_id' => 'new-credential-id',
        'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
    ]);

    $this->actingAs($user)->session(['auth.password_confirmed_at' => time()]);

    $response = $this->fromRoute('dashboard')
        ->get(route('user-passkey.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('passkeys.0.name', 'New Key')
            ->where('passkeys.1.name', 'Old Key'));
});

it('requires password confirmation to render passkeys page', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->fromRoute('dashboard')
        ->get(route('user-passkey.index'));

    $response->assertRedirectToRoute('password.confirm');
});

it('hides passkeys page when passkeys are disabled', function (): void {
    config(['fortify.features' => [Features::twoFactorAuthentication()]]);

    $user = User::factory()->create();

    $this->actingAs($user)->session(['auth.password_confirmed_at' => time()]);

    $response = $this->get(route('user-passkey.index'));

    $response->assertNotFound();
});

it('requires authentication to render passkeys page', function (): void {
    $response = $this->get(route('user-passkey.index'));

    $response->assertRedirectToRoute('login');
});

it('does not expose passkey endpoints when passkeys are disabled', function (): void {
    config(['fortify.features' => [Features::twoFactorAuthentication()]]);

    $response = $this->get(route('well-known.passkeys'));

    $response->assertNotFound();
});

it('exposes passkey endpoints', function (): void {
    $response = $this->get(route('well-known.passkeys'));

    $response->assertOk()
        ->assertExactJson([
            'enroll' => route('user-passkey.index'),
            'manage' => route('user-passkey.index'),
        ]);
});
