<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_visitor_is_redirected_to_login_page()
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'))
            ;
    }

    /**
     * @test
     */
    public function test_logged_user_can_see_home()
    {
        $user = factory(User::class)->make([
            'name' => 'Logged user'
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee($user->name)
            ;
    }
}
