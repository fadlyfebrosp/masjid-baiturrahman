<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Storage;

class ProgramTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_create_program()
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

       $response = $this
        ->actingAs($admin)
        ->post(route('admin.program.store'), [
            'kategori'       => 'Infak', // ← HARUS SESUAI ENUM
            'judul'          => 'Program Renovasi Masjid',
            'deskripsi'      => 'Deskripsi program',
            'foto'           => UploadedFile::fake()->image('program.jpg'),
            'target_waktu'   => now()->addDays(10)->toDateString(),
            'target_dana'    => 10000000,
            'min_donasi'     => 10000,
            'open_goals'     => false,
            'custom_nominal' => [10000, 50000],
        ]);
        $response->assertStatus(302);

        $program = Program::latest()->firstOrFail();

        $response
            ->assertRedirect(route('admin.program.show', $program->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('programs', [
            'judul'    => 'Program Renovasi Masjid',
            'kategori' => 'Infak',
        ]);

        Storage::disk('public')->assertExists($program->foto);
    }

    #[Test]
    public function non_admin_cannot_create_program()
    {
        $user = User::factory()->create([
            'role' => 'jamaah',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('admin.program.store'), [
                'kategori' => 'zakat',
                'judul'    => 'Program Ilegal',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseCount('programs', 0);
    }

    #[Test]
    public function create_program_fails_when_required_fields_missing()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.program.create'))
            ->post(route('admin.program.store'), [
                'kategori' => 'zakat',
                // judul kosong
            ]);

        $response
            ->assertRedirect(route('admin.program.create'))
            ->assertSessionHasErrors('judul');

        $this->assertDatabaseCount('programs', 0);
    }
}
