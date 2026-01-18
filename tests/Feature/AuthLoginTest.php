<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_is_redirected_to_admin_dashboard_after_login()
    {
        $admin = User::factory()->create([
            'email'    => 'admin@proton.me',
            'password' => Hash::make('4dM!nTH36E5T'),
            'role'     => 'admin',
        ]);

        $response = $this->post('/login', [
            'login'    => 'admin@proton.me',
            'password' => '4dM!nTH36E5T',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    #[Test]
    public function finance_is_redirected_to_finance_dashboard_after_login()
    {
        $finance = User::factory()->create([
            'email'    => 'finance@proton.me',
            'password' => Hash::make('F!n4nC3M4sJ1d'),
            'role'     => 'finance',
        ]);

        $response = $this->post('/login', [
            'login'    => 'finance@proton.me',
            'password' => 'F!n4nC3M4sJ1d',
        ]);

        $response->assertRedirect('/finance/dashboard');
        $this->assertAuthenticatedAs($finance);
    }

    #[Test]
    public function jamaah_is_redirected_to_home_after_login()
    {
        $jamaah = User::factory()->create([
            'email'    => 'jamaah@proton.me',
            'password' => Hash::make('Password123'),
            'role'     => 'jamaah',
        ]);

        $response = $this->post('/login', [
            'login'    => 'jamaah@proton.me',
            'password' => 'Password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($jamaah);
    }

    #[Test]
    public function login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'email'    => 'admin@proton.me',
            'password' => Hash::make('correct-password'),
            'role'     => 'admin',
        ]);

        $response = $this->from('/login')->post('/login', [
            'login'    => 'admin@proton.me',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }
}
