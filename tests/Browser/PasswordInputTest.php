<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Validation\Rules\Password;

it('toggles password visibility', function (): void {
    $page = visit(route('login'));

    $page->assertAttribute('#password', 'type', 'password')
        ->click('@toggle-password-visibility')
        ->assertAttribute('#password', 'type', 'text')
        ->click('@toggle-password-visibility')
        ->assertAttribute('#password', 'type', 'password')
        ->assertNoJavaScriptErrors();
});

it('hints the password rules only on new passwords', function (): void {
    $rules = Password::defaults()->toPasswordRulesString();

    visit(route('register'))
        ->assertAttribute('#password', 'passwordrules', $rules)
        ->assertAttribute('#password_confirmation', 'passwordrules', $rules)
        ->assertAttribute('#password', 'aria-describedby', 'password-hints')
        ->assertSee('At least 12 characters')
        ->assertNoJavaScriptErrors();

    visit(route('login'))
        ->assertAttributeMissing('#password', 'passwordrules')
        ->assertDontSee('At least 12 characters')
        ->assertNoJavaScriptErrors();
});

it('refocuses the password after a wrong password when deleting the account', function (): void {
    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    $page = visit(route('user-profile.edit'));

    $page->click('@delete-user-button')
        ->fill('password', 'wrong-password')
        ->click('@confirm-delete-user-button')
        ->assertSee('The password is incorrect.')
        ->assertScript('document.activeElement?.id', 'password')
        ->assertNoJavaScriptErrors();
});
