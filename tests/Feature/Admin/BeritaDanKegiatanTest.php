<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\BeritaDanKegiatan;
use App\Models\BeritaFoto;
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

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.beritadankegiatan.store'), [
            'judul'      => 'Kegiatan Gotong Royong',
            'namamasjid' => 'Masjid Al Ikhlas',
            'tanggal'    => now()->toDateString(),
            'kategori'   => 'Kegiatan',
            'deskripsi'  => 'Kerja bakti membersihkan masjid',
            'foto'       => [
                UploadedFile::fake()->image('kegiatan1.jpg'),
                UploadedFile::fake()->image('kegiatan2.jpg'),
            ],
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('beritadankegiatan', [
            'judul'      => 'Kegiatan Gotong Royong',
            'namamasjid' => 'Masjid Al Ikhlas',
            'kategori'   => 'Kegiatan',
        ]);

        $berita = BeritaDanKegiatan::firstOrFail();

        $this->assertDatabaseCount('berita_fotos', 2);

        $this->assertDatabaseHas('berita_fotos', [
            'berita_dan_kegiatan_id' => $berita->id,
        ]);
    }

    #[Test]
    public function admin_can_update_berita_dan_kegiatan()
    {
        Storage::fake('public');

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $berita = BeritaDanKegiatan::create([
            'judul'      => 'Judul Lama',
            'namamasjid' => 'Masjid Lama',
            'tanggal'    => now()->toDateString(),
            'kategori'   => 'Berita',
            'deskripsi'  => 'Deskripsi lama',
        ]);

        BeritaFoto::create([
            'berita_dan_kegiatan_id' => $berita->id,
            'path' => UploadedFile::fake()
                ->image('old.jpg')
                ->store('berita', 'public'),
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.beritadankegiatan.update', $berita->id), [
                'judul'      => 'Judul Baru',
                'namamasjid' => 'Masjid Baru',
                'tanggal'    => now()->toDateString(),
                'kategori'   => 'Kegiatan',
                'deskripsi'  => 'Deskripsi baru',
                'foto'       => [
                    UploadedFile::fake()->image('new1.jpg'),
                    UploadedFile::fake()->image('new2.jpg'),
                ],
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('beritadankegiatan', [
            'id'         => $berita->id,
            'judul'      => 'Judul Baru',
            'namamasjid' => 'Masjid Baru',
            'kategori'   => 'Kegiatan',
        ]);

        $this->assertDatabaseCount('berita_fotos', 3);
    }

    #[Test]
    public function admin_can_delete_berita_dan_kegiatan()
    {
        Storage::fake('public');

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $berita = BeritaDanKegiatan::create([
            'judul'      => 'Berita Hapus',
            'namamasjid' => 'Masjid Test',
            'tanggal'    => now()->toDateString(),
            'kategori'   => 'Berita',
            'deskripsi'  => 'Akan dihapus',
        ]);

        $foto = UploadedFile::fake()
            ->image('hapus.jpg')
            ->store('berita', 'public');

        BeritaFoto::create([
            'berita_dan_kegiatan_id' => $berita->id,
            'path' => $foto,
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.beritadankegiatan.destroy', $berita->id));

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('beritadankegiatan', [
            'id' => $berita->id,
        ]);

        $this->assertDatabaseMissing('berita_fotos', [
            'berita_dan_kegiatan_id' => $berita->id,
        ]);
    }

    #[Test]
    public function non_admin_cannot_create_berita_dan_kegiatan()
    {
        /** @var \App\Models\User $user */
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
        /** @var \App\Models\User $admin */
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
            ]);
    }
}
