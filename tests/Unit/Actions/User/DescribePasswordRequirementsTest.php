<?php

declare(strict_types=1);

use App\Actions\User\DescribePasswordRequirements;
use Illuminate\Validation\Rules\Password;

it('describes the default password rules', function (): void {
    $action = resolve(DescribePasswordRequirements::class);

    expect($action->handle())->toBe([
        'rules' => Password::defaults()->toPasswordRulesString(),
        'hints' => ['At least 12 characters'],
    ]);
});

it('describes the production password rules', function (): void {
    app()['env'] = 'production';

    $action = resolve(DescribePasswordRequirements::class);

    expect($action->handle())->toBe([
        'rules' => Password::defaults()->toPasswordRulesString(),
        'hints' => [
            'At least 12 characters',
            'Upper and lowercase letters',
            'At least one number',
            'At least one symbol',
            'Not found in a known data leak',
        ],
    ]);
});

it('describes every enabled password rule', function (): void {
    Password::defaults(fn (): Password => Password::min(10)->mixedCase()->numbers()->symbols()->uncompromised());

    $action = resolve(DescribePasswordRequirements::class);

    expect($action->handle()['hints'])->toBe([
        'At least 10 characters',
        'Upper and lowercase letters',
        'At least one number',
        'At least one symbol',
        'Not found in a known data leak',
    ]);
});

it('describes the letters rule when mixed case is not required', function (): void {
    Password::defaults(fn (): Password => Password::min(8)->letters());

    $action = resolve(DescribePasswordRequirements::class);

    expect($action->handle()['hints'])->toBe([
        'At least 8 characters',
        'At least one letter',
    ]);
});

it('translates the password hints', function (): void {
    app()->setLocale('pt');

    Password::defaults(fn (): Password => Password::min(12)->letters()->uncompromised());

    $action = resolve(DescribePasswordRequirements::class);

    expect($action->handle()['hints'])->toBe([
        'Pelo menos 12 caracteres',
        'Pelo menos uma letra',
        'Não encontrada em nenhum leak de informação conhecido',
    ]);
});
