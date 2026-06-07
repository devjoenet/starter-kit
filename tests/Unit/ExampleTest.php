<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('user passwords are hashed', function (): void {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    expect(Hash::check('password', $user->password))->toBeTrue();
});
