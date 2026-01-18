<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\BeritaDanKegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class BeritaDanKegiatanTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_create_berita_dan_kegiatan()
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.beritadankegiatan.store'), [
                'judul'       => 'Kegiatan Gotong Royong',
                'namamasjid'  => 'Masjid Al Ikhlas',
                'tanggal'     => now()->toDateString(),
                'kategori'    => 'Kegiatan',
                'deskripsi'   => 'Kerja bakti membersihkan masjid',
                'foto'        => UploadedFile::fake()->image('kegiatan.jpg'),
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('beritadankegiatan', [
            'judul'      => 'Kegiatan Gotong Royong',
            'namamasjid' => 'Masjid Al Ikhlas',
            'kategori'   => 'Kegiatan',
        ]);

        $data = BeritaDanKegiatan::firstOrFail();
        Storage::disk('public')->assertExists($data->foto);
    }

    #[Test]
    public function admin_can_update_berita_dan_kegiatan()
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $data = BeritaDanKegiatan::create([
            'judul'      => 'Judul Lama',
            'namamasjid' => 'Masjid Lama',
            'tanggal'    => now()->toDateString(),
            'kategori'   => 'Berita',
            'deskripsi'  => 'Deskripsi lama',
            'foto'       => UploadedFile::fake()
                ->image('old.jpg')
                ->store('berita-foto', 'public'),
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.beritadankegiatan.update', $data->id), [
                'judul'      => 'Judul Baru',
                'namamasjid' => 'Masjid Baru',
                'tanggal'    => now()->toDateString(),
                'kategori'   => 'Kegiatan',
                'deskripsi'  => 'Deskripsi baru',
                'foto'       => UploadedFile::fake()->image('new.jpg'),
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('beritadankegiatan', [
            'id'         => $data->id,
            'judul'      => 'Judul Baru',
            'namamasjid' => 'Masjid Baru',
            'kategori'   => 'Kegiatan',
        ]);
    }

    #[Test]
    public function admin_can_delete_berita_dan_kegiatan()
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $data = BeritaDanKegiatan::create([
            'judul'      => 'Berita Hapus',
            'namamasjid' => 'Masjid Test',
            'tanggal'    => now()->toDateString(),
            'kategori'   => 'Berita',
            'deskripsi'  => 'Akan dihapus',
            'foto'       => UploadedFile::fake()
                ->image('hapus.jpg')
                ->store('berita-foto', 'public'),
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.beritadankegiatan.destroy', $data->id));

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('beritadankegiatan', [
            'id' => $data->id,
        ]);
    }

    #[Test]
    public function non_admin_cannot_create_berita_dan_kegiatan()
    {
        $user = User::factory()->create([
            'role' => 'jamaah',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('admin.beritadankegiatan.store'), [
                'judul' => 'Berita Ilegal',
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseCount('beritadankegiatan', 0);
    }

    #[Test]
    public function create_fails_when_required_fields_missing()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.beritadankegiatan.store'), []);

        $response
            ->assertRedirect()
            ->assertSessionHasErrors([
                'judul',
                'namamasjid',
                'tanggal',
                'kategori',
                'deskripsi',
            ]);
    }
}
