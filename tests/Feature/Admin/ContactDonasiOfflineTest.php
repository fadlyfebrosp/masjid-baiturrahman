<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Contactdonasioffline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactDonasiOfflineTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    #[Test]
    public function admin_can_view_contact_donasi_index()
    {
        Contactdonasioffline::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
        ->get(route('admin.contactdonasioffline.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.contactdonasioffline.index');
        $response->assertViewHas('contacts');
        }

    #[Test]
    public function admin_can_create_contact_donasi()
    {
        $payload = [
            'name'     => 'Ahmad Donatur',
            'email'    => 'ahmad@test.com',
            'phone'    => '08123456789',
            'gender'   => 'male',
            'country'  => 'Indonesia',
            'province' => 'Jawa Barat',
            'city'     => 'Bandung',
            'address'  => 'Jl. Testing',
            ];

            $response = $this->actingAs($this->admin)
            ->post(route('admin.contactdonasioffline.store'), $payload);

            $response->assertRedirect(route('admin.contactdonasioffline.index'));

            $this->assertDatabaseHas('contactdonasiofflines', [
                'email' => 'ahmad@test.com',
                'phone' => '08123456789',
            ]);
        }

    #[Test]
    public function validation_fails_when_required_fields_missing()
    {
        $response = $this->actingAs($this->admin)
        ->post(route('admin.contactdonasioffline.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'phone',
            'gender',
        ]);
    }

    #[Test]
    public function admin_can_update_contact_donasi()
    {
        $contact = Contactdonasioffline::factory()->create([
            'name' => 'Nama Lama',
            ]);

            $response = $this->actingAs($this->admin)
            ->put(route('admin.contactdonasioffline.update', $contact), [
                'name'   => 'Nama Baru',
                'email'  => $contact->email,
                'phone'  => $contact->phone,
                'gender' => 'male',
                ]);

                $response->assertRedirect(route('admin.contactdonasioffline.index'));

                $this->assertDatabaseHas('contactdonasiofflines', [
                    'id'   => $contact->id,
                    'name' => 'Nama Baru',
                ]);
    }

    #[Test]
    public function admin_can_delete_contact_donasi()
    {
        $contact = Contactdonasioffline::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.contactdonasioffline.destroy', $contact));

        $response->assertRedirect(route('admin.contactdonasioffline.index'));

        $this->assertDatabaseMissing('contactdonasiofflines', [
            'id' => $contact->id,
        ]);
    }
}
