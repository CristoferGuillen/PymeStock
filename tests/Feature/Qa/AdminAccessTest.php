<?php

namespace Tests\Feature\Qa;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_does_not_return_server_error(): void
    {
        $response = $this->get('/');

        $this->assertFalse(
            $response->isServerError(),
            'La página principal está devolviendo un error 500.'
        );
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect();
    }

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
    }

    public function test_seeded_admin_user_does_not_break_admin_panel(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@pymestock.test')->firstOrFail();

        $response = $this->actingAs($admin, 'web')->get('/admin');

        $this->assertFalse(
            $response->isServerError(),
            'El panel admin devuelve error 500 al acceder con el usuario administrador.'
        );
    }
}