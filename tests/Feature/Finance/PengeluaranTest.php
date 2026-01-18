<?php

namespace Tests\Feature\Finance;

use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PengeluaranTest extends TestCase
{
    use RefreshDatabase;

    private function financeUser()
    {
        return User::factory()->create([
            'role' => 'finance'
        ]);
    }

    #[Test]
    public function finance_can_view_pengeluaran_page()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->get(route('finance.pengeluaran.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function finance_can_store_pengeluaran()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->post(route('finance.pengeluaran.store'), [
                'tanggal'     => now()->toDateString(),
                'kategori'    => 'Operasional',
                'jumlah_dana' => 300000,
                'keterangan'  => 'Biaya listrik'
            ]);

        $response
            ->assertRedirect(route('finance.pengeluaran.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pengeluarans', [
            'kategori'    => 'Operasional',
            'jumlah_dana' => 300000
        ]);
    }

    #[Test]
    public function finance_can_update_pengeluaran()
    {
        $user = $this->financeUser();

        $pengeluaran = Pengeluaran::create([
            'tanggal'     => now(),
            'kategori'    => 'Awal',
            'jumlah_dana' => 100000,
            'keterangan'  => null
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('finance.pengeluaran.update', $pengeluaran->id), [
                'tanggal'     => now()->toDateString(),
                'kategori'    => 'Perawatan',
                'jumlah_dana' => 250000,
                'keterangan'  => 'Perbaikan AC'
            ]);

        $response
            ->assertRedirect(route('finance.pengeluaran.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pengeluarans', [
            'id'          => $pengeluaran->id,
            'kategori'    => 'Perawatan',
            'jumlah_dana' => 250000
        ]);
    }

    #[Test]
    public function finance_can_delete_pengeluaran()
    {
        $user = $this->financeUser();

        $pengeluaran = Pengeluaran::create([
            'tanggal'     => now(),
            'kategori'    => 'Hapus',
            'jumlah_dana' => 75000,
            'keterangan'  => null
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('finance.pengeluaran.destroy', $pengeluaran->id));

        $response
            ->assertRedirect(route('finance.pengeluaran.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('pengeluarans', [
            'id' => $pengeluaran->id
        ]);
    }

    #[Test]
    public function store_pengeluaran_validation_fails()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->post(route('finance.pengeluaran.store'), []);

        $response->assertSessionHasErrors([
            'tanggal',
            'kategori',
            'jumlah_dana'
        ]);
    }
}
