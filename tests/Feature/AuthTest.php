<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_login()
    {
        $user = User::factory()->make([
            'password_confirmation' => '123123123123',
            'accept1' => true,
            'accept2' => true
        ]);
        $response = $this->post(route('employers.store'), $user->toArray());
        $response->assertCreated();

        $userInDb = User::first();

        $loginData = [
            'email' => $user['email'],
            'password' => $user['password'],
            'remember' => false
        ];
        
        $response = $this->post(route('login'), $loginData);
        $response->assertOk();
        $response->assertJson([
            'canLogin' => true,
            'user_id' => $userInDb->id,
            'role' => $userInDb->role,
            'message' => 'Sikerült a bejelentkezés!'
        ])->assertJsonStructure(['token']);
    }
}
