<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_passwords_are_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $this->assertTrue(Hash::check('password', $user->password));
    }
}
