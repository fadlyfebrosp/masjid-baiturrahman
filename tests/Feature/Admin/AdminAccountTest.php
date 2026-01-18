<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_view_account_list()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        User::factory()->count(3)->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.account'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.account');
        $response->assertViewHas('accounts');
    }

    #[Test]
    public function admin_can_create_account()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.account.store'), [
                'name'                  => 'User Baru',
                'email'                 => 'userbaru@example.com',
                'phone'                 => '08123456789',
                'gender'                => 'Laki-laki',
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'role'                  => 'jamaah',
            ]);

        $response
            ->assertRedirect(route('admin.account'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'userbaru@example.com',
            'role'  => 'jamaah',
        ]);
    }

    #[Test]
    public function admin_can_update_account()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'jamaah',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.account.update', $user->id), [
                'name'                  => 'Nama Diupdate',
                'email'                 => 'update@example.com',
                'phone'                 => '0899999999',
                'gender'                => 'Perempuan',
                'password'              => 'newpassword',
                'password_confirmation' => 'newpassword',
                'role'                  => 'finance',
            ]);

        $response
            ->assertRedirect(route('admin.account'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'email' => 'update@example.com',
            'role'  => 'finance',
        ]);

        $this->assertTrue(
            Hash::check('newpassword', $user->fresh()->password)
        );
    }

    #[Test]
    public function admin_can_update_role_via_ajax()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'jamaah',
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route('admin.account.updateRole', $user->id),
                ['role' => 'finance'],
                ['Accept' => 'application/json']
            );

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'role'   => 'finance',
            ]);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'role' => 'finance',
        ]);
    }

    #[Test]
    public function admin_can_delete_account()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.account.destroy', $user->id));

        $response
            ->assertRedirect(route('admin.account'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function non_admin_cannot_access_account_management()
    {
        $user = User::factory()->create([
            'role' => 'jamaah',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.account'));

        $response->assertStatus(403);
    }

    #[Test]
    public function create_account_fails_when_required_fields_missing()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.account.store'), []);

        $response
            ->assertRedirect()
            ->assertSessionHasErrors([
                'name',
                'email',
                'password',
                'role',
            ]);
    }
}
