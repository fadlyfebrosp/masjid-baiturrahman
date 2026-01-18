<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Program;
use App\Models\Contactdonasioffline;
use App\Models\DonasiOffline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DonasiOfflineTest extends TestCase
{
    use RefreshDatabase;

    private function admin()
    {
        return User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_view_donasi_offline_index()
    {
        $admin = $this->admin();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.donasioffline.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_create_donasi_offline()
    {
        $admin   = $this->admin();
        $program = Program::factory()->create();
        $contact = Contactdonasioffline::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.donasioffline.store'), [
                'program_id'              => $program->id,
                'contactdonasioffline_id' => $contact->id,
                'nominal'                 => 150000,
                'metode_pembayaran'       => 'CASH',
                'tanggal_transaksi'       => now(),
                'status'                  => 'SELESAI',
            ]);

        $response
            ->assertRedirect(route('admin.donasioffline.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('donasi_offlines', [
            'program_id' => $program->id,
            'nominal'    => 150000,
            'status'     => 'SELESAI',
        ]);
    }

    #[Test]
    public function non_admin_cannot_create_donasi_offline()
    {
        $user = User::factory()->create(['role' => 'jamaah']);

        $response = $this
            ->actingAs($user)
            ->post(route('admin.donasioffline.store'), []);

        $response->assertStatus(403);
    }

    #[Test]
    public function create_donasi_offline_fails_when_required_fields_missing()
    {
        $admin = $this->admin();

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.donasioffline.store'), []);

        $response->assertSessionHasErrors([
            'program_id',
            'contactdonasioffline_id',
            'nominal',
            'metode_pembayaran',
            'tanggal_transaksi',
            'status',
        ]);
    }
}
