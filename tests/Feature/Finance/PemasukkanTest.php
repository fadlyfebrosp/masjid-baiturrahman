<?php

namespace Tests\Feature\Finance;

use App\Models\Pemasukkan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PemasukkanTest extends TestCase
{
    use RefreshDatabase;

    private function financeUser()
    {
        return User::factory()->create([
            'role' => 'finance'
        ]);
    }

    #[Test]
    public function finance_can_view_pemasukkan_page()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->get(route('finance.pemasukkan.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function finance_can_store_pemasukkan()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->post(route('finance.pemasukkan.store'), [
                'tanggal'     => now()->toDateString(),
                'sumber_dana' => 'Donasi Jamaah',
                'jumlah_dana' => 500000,
                'keterangan'  => 'Infaq Jumat'
            ]);

        $response
            ->assertRedirect(route('finance.pemasukkan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pemasukkans', [
            'sumber_dana' => 'Donasi Jamaah',
            'jumlah_dana' => 500000
        ]);
    }

    #[Test]
    public function finance_can_update_pemasukkan()
    {
        $user = $this->financeUser();

        $pemasukkan = Pemasukkan::create([
            'tanggal'     => now(),
            'sumber_dana' => 'Donasi Lama',
            'jumlah_dana' => 100000,
            'keterangan'  => null
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('finance.pemasukkan.update', $pemasukkan->id), [
                'tanggal'     => now()->toDateString(),
                'sumber_dana' => 'Donasi Update',
                'jumlah_dana' => 250000,
                'keterangan'  => 'Update data'
            ]);

        $response
            ->assertRedirect(route('finance.pemasukkan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pemasukkans', [
            'id'          => $pemasukkan->id,
            'jumlah_dana' => 250000
        ]);
    }

    #[Test]
    public function finance_can_delete_pemasukkan()
    {
        $user = $this->financeUser();

        $pemasukkan = Pemasukkan::create([
            'tanggal'     => now(),
            'sumber_dana' => 'Donasi Hapus',
            'jumlah_dana' => 75000,
            'keterangan'  => null
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('finance.pemasukkan.destroy', $pemasukkan->id));

        $response
            ->assertRedirect(route('finance.pemasukkan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('pemasukkans', [
            'id' => $pemasukkan->id
        ]);
    }

    #[Test]
    public function store_pemasukkan_validation_fails()
    {
        $user = $this->financeUser();

        $response = $this
            ->actingAs($user)
            ->post(route('finance.pemasukkan.store'), []);

        $response->assertSessionHasErrors([
            'tanggal',
            'sumber_dana',
            'jumlah_dana'
        ]);
    }
}
