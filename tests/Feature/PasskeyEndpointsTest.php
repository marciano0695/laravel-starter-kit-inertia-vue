<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Passkeys\Passkey;

it('returns login options for the configured relying party', function (): void {
    $response = $this->getJson(route('passkey.login-options'));

    $response->assertOk()
        ->assertJsonPath('options.rpId', parse_url(config()->string('app.url'), PHP_URL_HOST))
        ->assertJsonStructure(['options' => ['challenge']]);
});

it('translates passkey errors to portuguese', function (): void {
    app()->setLocale('pt');

    $response = $this->postJson(route('passkey.login'), [
        'credential' => [
            'id' => 'credential-id',
            'rawId' => 'credential-id',
            'type' => 'public-key',
            'response' => ['clientDataJSON' => 'not-webauthn'],
        ],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credential' => 'Formato de credencial inválido.']);
});

it('returns registration options for a user who confirmed their password', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->session(['auth.password_confirmed_at' => time()]);

    $response = $this->getJson(route('passkey.registration-options'));

    $response->assertOk()
        ->assertJsonStructure(['options' => ['challenge', 'rp', 'user']]);
});

it('requires password confirmation to get registration options', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->getJson(route('passkey.registration-options'));

    $response->assertStatus(423);
});

it('may delete own passkey', function (): void {
    $user = User::factory()->create();

    $passkey = $user->passkeys()->create([
        'name' => 'MacBook Pro',
        'credential_id' => 'credential-id',
        'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
    ]);

    $this->actingAs($user)->session(['auth.password_confirmed_at' => time()]);

    $response = $this->fromRoute('user-passkey.index')
        ->delete(route('passkey.destroy', $passkey));

    // Inertia needs a redirect here; a JSON response would surface as an error modal.
    $response->assertRedirectToRoute('user-passkey.index')
        ->assertSessionHas('status', 'passkey-deleted');

    expect(Passkey::query()->find($passkey->id))->toBeNull();
});

it('cannot delete another users passkey', function (): void {
    $owner = User::factory()->create();

    $passkey = $owner->passkeys()->create([
        'name' => 'MacBook Pro',
        'credential_id' => 'credential-id',
        'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
    ]);

    $this->actingAs(User::factory()->create())->session(['auth.password_confirmed_at' => time()]);

    $response = $this->delete(route('passkey.destroy', $passkey));

    $response->assertForbidden();

    expect(Passkey::query()->find($passkey->id))->not->toBeNull();
});
