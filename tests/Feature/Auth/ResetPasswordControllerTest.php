<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_visitor_sees_reset_password_page()
    {
        $this->get(route('password.reset', [
                'token' => 'reset-password-token',
            ]))
            ->assertOk()
            ->assertSee(__('Reset Password'))
            ->assertSee(__('Confirm Password'))
            ;
    }

    /**
     * @test
     */
    public function test_existing_user_can_reset_password()
    {
        $user = factory(User::class)->create();

        $token = Password::broker()->createToken($user);

        $newPassword = 'new-password';

        $this->followingRedirects()
            ->from(route('password.reset', [
                'token' => $token,
            ]))
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $user->email,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ])
            ->assertOk()
            ->assertSee(__('Your password has been reset!'))
            ;

        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));

        $this->assertAuthenticatedAs($user);
    }
}
