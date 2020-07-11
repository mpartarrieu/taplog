<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_visitor_sees_remember_password_link_on_login_page()
    {
        $this->followingRedirects()
            ->get(route('login'))
            ->assertOk()
            ->assertSee(__('Forgot Your Password?'))
            ;
    }

    /**
     * @test
     */
    public function test_visitor_sees_remember_password_page()
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee(__('Reset Password'))
            ;
    }

    /**
     * @test
     */
    public function test_existing_user_can_remember_password()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $this->followingRedirects()
            ->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => $user->email
            ])
            ->assertOk()
            ->assertSee(__('We have emailed your password reset link!'))
            ;
        
        Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class);
    }
}
