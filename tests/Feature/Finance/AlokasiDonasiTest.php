<?php

namespace Tests\Feature\Finance;

use App\Models\AlokasiDonasi;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AlokasiDonasiTest extends TestCase
{
    use RefreshDatabase;

    private function financeUser()
    {
        return User::factory()->create([
            'role' => 'finance'
        ]);
    }

    private function program()
    {
        return Program::factory()->create();
    }

    #[Test]
    public function finance_can_view_alokasi_donasi_index()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->get(route('finance.alokasidonasi.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function finance_can_store_alokasi_donasi()
    {
        $user = $this->financeUser();
        $program = $this->program();

        $response = $this
            ->actingAs($user)
            ->post(route('finance.alokasidonasi.store'), [
                'program_id'    => $program->id,
                'nama_kegiatan' => 'Santunan Anak Yatim',
                'jumlah'        => 500000,
                'tanggal'       => now()->toDateString(),
                'keterangan'    => 'Dana bulan Ramadhan'
            ]);

        $response
            ->assertSessionHas('success');

        $this->assertDatabaseHas('alokasi_donasi', [
            'program_id'    => $program->id,
            'nama_kegiatan' => 'Santunan Anak Yatim',
            'jumlah'        => 500000
        ]);
    }

    #[Test]
    public function finance_can_update_alokasi_donasi()
    {
        $user = $this->financeUser();
        $program = $this->program();

        $alokasi = AlokasiDonasi::create([
            'program_id'    => $program->id,
            'nama_kegiatan' => 'Awal',
            'jumlah'        => 100000,
            'tanggal'       => now(),
            'keterangan'    => null,
            'created_by'    => $user->id
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('finance.alokasidonasi.update', $alokasi->id), [
                'program_id'    => $program->id,
                'nama_kegiatan' => 'Renovasi Masjid',
                'jumlah'        => 750000,
                'tanggal'       => now()->toDateString(),
                'keterangan'    => 'Tahap 1'
            ]);

        $response
            ->assertSessionHas('success');

        $this->assertDatabaseHas('alokasi_donasi', [
            'id'            => $alokasi->id,
            'nama_kegiatan' => 'Renovasi Masjid',
            'jumlah'        => 750000
        ]);
    }

    #[Test]
    public function finance_can_delete_alokasi_donasi()
    {
        $user = $this->financeUser();
        $program = $this->program();

        $alokasi = AlokasiDonasi::create([
            'program_id'    => $program->id,
            'nama_kegiatan' => 'Hapus',
            'jumlah'        => 200000,
            'tanggal'       => now(),
            'keterangan'    => null,
            'created_by'    => $user->id
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('finance.alokasidonasi.destroy', $alokasi->id));

        $response
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('alokasi_donasi', [
            'id' => $alokasi->id
        ]);
    }

    #[Test]
    public function store_alokasi_donasi_validation_fails()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->post(route('finance.alokasidonasi.store'), []);

        $response->assertSessionHasErrors([
            'program_id',
            'nama_kegiatan',
            'jumlah',
            'tanggal'
        ]);
    }
}
