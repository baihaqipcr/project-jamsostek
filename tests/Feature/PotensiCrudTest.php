<?php

namespace Tests\Feature;

use App\Models\Potensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PotensiCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_visiting_root_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_visiting_root_is_redirected_to_potensi(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect('/potensi');
    }

    public function test_authenticated_user_can_view_potensi_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/potensi');

        $response->assertOk();
    }

    public function test_unknown_page_renders_not_found(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/halaman-tidak-ada');

        $response->assertNotFound();
    }

    public function test_authenticated_user_can_create_potensi(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/potensi', [
            'tanggal_input' => '2026-08-31',
            'nama_usaha' => 'PT Maju Bersama',
            'segmen' => 'PU',
            'uraian' => 'Potensi pekerja formal di lokasi baru.',
            'alamat' => 'Jl. Merdeka No. 10',
            'estimasi_tk' => 25,
            'estimasi_upah' => 25000000,
            'estimasi_iuran' => 2500000,
            'status_tindak_lanjut' => 'Belum dihubungi',
            'programs' => ['JKK', 'JHT'],
        ]);

        $response->assertRedirect('/potensi');
        $this->assertDatabaseHas('potensi', [
            'nama_usaha' => 'PT Maju Bersama',
            'user_id' => $user->id,
            'segmen' => 'PU',
        ]);
        $this->assertDatabaseHas('program_potensi', [
            'jenis_program' => 'JKK',
        ]);
        $this->assertDatabaseHas('program_potensi', [
            'jenis_program' => 'JHT',
        ]);
    }

    public function test_authenticated_user_can_generate_sp1(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/potensi', [
            'tanggal_input' => '2026-08-31',
            'nama_usaha' => 'PT Cetak SP1',
            'segmen' => 'PU',
            'uraian' => 'Uji cetak sp1',
            'alamat' => 'Jl. Tes No. 1',
            'estimasi_tk' => 10,
            'estimasi_upah' => 10000000,
            'estimasi_iuran' => 1000000,
            'status_tindak_lanjut' => 'Belum dihubungi',
        ]);

        $response->assertRedirect('/potensi');

        $potensi = \App\Models\Potensi::firstWhere('nama_usaha', 'PT Cetak SP1');

        $resp = $this->actingAs($user)->get(route('potensi.sp1', $potensi));

        $resp->assertStatus(200);

        $this->assertDatabaseHas('potensi', [
            'id' => $potensi->id,
            'status_sp1' => 1,
        ]);
        $this->assertNotNull($potensi->fresh()->tanggal_cetak_sp1);
    }

    public function test_authenticated_user_can_generate_sp1_pdf_and_mark_as_printed(): void
    {
        $user = User::factory()->create();
        $potensi = Potensi::create([
            'user_id' => $user->id,
            'tanggal_input' => '2026-08-31',
            'nama_usaha' => 'CV Sumber Rezeki',
            'segmen' => 'BPU',
            'uraian' => 'Potensi besar untuk pemasok',
            'alamat' => 'Jl. Cikini No. 5',
            'latitude' => -6.2000,
            'longitude' => 106.8500,
            'estimasi_tk' => 18,
            'estimasi_upah' => 15000000,
            'estimasi_iuran' => 1200000,
            'status_tindak_lanjut' => 'Belum dihubungi',
        ]);

        $response = $this->actingAs($user)->get(route('potensi.sp1', $potensi));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertDatabaseHas('potensi', [
            'id' => $potensi->id,
            'status_sp1' => true,
        ]); 
    }

    public function test_authenticated_user_can_export_potensi_excel(): void
    {
        $user = User::factory()->create();

        Potensi::create([
            'user_id' => $user->id,
            'tanggal_input' => '2026-08-31',
            'nama_usaha' => 'Export Usaha 1',
            'segmen' => 'PU',
            'uraian' => 'Testing export',
            'alamat' => 'Jl. Export No.1',
            'estimasi_tk' => 5,
            'estimasi_upah' => 5000000,
            'estimasi_iuran' => 500000,
            'status_tindak_lanjut' => 'Belum dihubungi',
        ]);

        $response = $this->actingAs($user)->get(route('potensi.export'));

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('Content-Type'), 'application/vnd.openxmlformats-officedocument')
        );
    }
}
