<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_visitor_can_access_login_page()
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('Login'))
            ;
    }

    /**
     * @test
     */
    public function test_visitor_see_required_fields_errors()
    {
        $this->get(route('login'));

        $this->followingRedirects()
            ->post(route('login'))
            ->assertOk()
            ->assertSee(__('validation.required', [ 'attribute' => 'email' ]))
            ->assertSee(__('validation.required', [ 'attribute' => 'password' ]))
            ;
    }

    /**
     * @test
     */
    public function test_visitor_can_not_login_with_wrong_password()
    {
        $user = factory(User::class)->create([
            'email' => 'user@email.com',
            'password' => Hash::make('user-password')
        ]);

        $this->get(route('login'));

        $this->followingRedirects()
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrong-password'
            ])
            ->assertOk()
            ->assertSee(__('auth.failed'))
            ;
    }

    /**
     * @test
     */
    public function test_visitor_can_login()
    {
        $userPassword = 'user-password';

        $user = factory(User::class)->create([
            'password' => Hash::make($userPassword)
        ]);

        $this->get(route('login'));

        $this->followingRedirects()
            ->post(route('login'), [
                'email' => $user->email,
                'password' => $userPassword
            ])
            ->assertOk()
            ->assertSee($user->name)
            ;

        $this->assertAuthenticated($guard = null);
    }

    /**
     * @test
     */
        /**
     * @return void
     */
    public function test_logged_user_can_logout()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('home'))
            ;

        $this->assertGuest();
    }

}
