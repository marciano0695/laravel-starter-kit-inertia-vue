<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('keeps the app layout mounted when moving between settings pages', function (): void {
    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    $page = visit(route('user-profile.edit'));
    $page->script("document.querySelector('[data-slot=\"sidebar-wrapper\"]').dataset.persisted = 'yes'");

    $page->click('a[href$="/settings/password"]')
        ->assertSee('Update password');

    $page->assertScript("document.querySelector('[data-slot=\"sidebar-wrapper\"]').dataset.persisted", 'yes')
        ->assertSee('Password settings')
        ->assertAttributeContains('a[href$="/settings/password"]', 'class', 'bg-muted')
        ->assertAttributeDoesntContain('a[href$="/settings/profile"]', 'class', 'bg-muted')
        ->assertNoJavaScriptErrors();
});

it('updates the auth layout heading when switching to a recovery code', function (): void {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'two_factor_secret' => encrypt('secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => now(),
    ]);

    $page = visit(route('login'));

    $page->fill('email', 'test@example.com')
        ->fill('password', 'password')
        ->click('@login-button')
        ->assertSee('Authentication Code');

    $page->click('login using a recovery code')
        ->assertSee('entering one of your emergency recovery codes')
        ->assertSeeIn('div > h1', 'Recovery Code')
        ->assertDontSeeIn('div > h1', 'Authentication Code')
        ->assertNoJavaScriptErrors();
});

it('focuses the first field after navigating to another auth page', function (): void {
    $page = visit(route('login'));

    $page->click('Sign up')
        ->assertSee('Create an account');

    $page->assertScript('document.activeElement?.id', 'name')
        ->assertNoJavaScriptErrors();
});
