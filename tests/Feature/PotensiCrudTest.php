<?php

namespace Tests\Feature;

use App\Models\Potensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

    public function test_export_matches_the_import_template_structure(): void
    {
        $user = User::factory()->create();
        $potensi = Potensi::create([
            'user_id' => $user->id,
            'tanggal_input' => '2026-09-10',
            'nama_usaha' => 'Usaha Export Terstandar',
            'npwp' => '123456789012345',
            'segmen' => 'PU',
            'uraian' => 'Bidang usaha export',
            'alamat' => 'Jl. Export No. 10',
            'latitude' => -6.2000000,
            'longitude' => 106.8500000,
            'estimasi_tk' => 12,
            'estimasi_upah' => 12500000,
            'estimasi_iuran' => 1250000,
            'status_tindak_lanjut' => 'Belum dihubungi',
            'catatan' => 'Catatan export',
        ]);
        $potensi->programPotensi()->createMany([
            ['jenis_program' => 'JKK'],
            ['jenis_program' => 'JKP'],
        ]);

        $response = $this->actingAs($user)->get(route('potensi.export'));
        $path = tempnam(sys_get_temp_dir(), 'potensi-export-');
        file_put_contents($path, $response->streamedContent());

        try {
            $sheet = IOFactory::load($path)->getActiveSheet();

            $this->assertSame([
                'Tanggal Input',
                'Nama Usaha / Perusahaan',
                'NPWP',
                'Segmen',
                'Uraian / Bidang Usaha',
                'Alamat Lengkap',
                'Latitude',
                'Longitude',
                'Estimasi Tenaga Kerja',
                'Estimasi Upah',
                'Estimasi Iuran',
                'Program JKK, JKM, JHT, JP, JKP',
                'Status Tindak Lanjut',
                'Catatan',
            ], $sheet->rangeToArray('A1:N1', null, true, false)[0]);
            $this->assertSame('Usaha Export Terstandar', $sheet->getCell('B2')->getValue());
            $this->assertSame('JKK, JKP', $sheet->getCell('L2')->getValue());
            $this->assertSame('yyyy-mm-dd', $sheet->getStyle('A2')->getNumberFormat()->getFormatCode());
            $this->assertSame('A2', $sheet->getFreezePane());
            $this->assertSame('0E7C66', $sheet->getStyle('A1')->getFill()->getStartColor()->getRGB());
        } finally {
            unlink($path);
        }
    }

    public function test_user_can_only_see_their_own_potensi(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $potensi = Potensi::create([
            'user_id' => $owner->id,
            'tanggal_input' => '2026-08-31',
            'nama_usaha' => 'Data Milik Owner',
            'segmen' => 'PU',
            'uraian' => 'Data privat',
            'alamat' => 'Jl. Owner No. 1',
            'estimasi_tk' => 5,
            'estimasi_upah' => 5000000,
            'estimasi_iuran' => 500000,
        ]);

        $index = $this->actingAs($otherUser)->get('/potensi');
        $index->assertOk();
        $this->assertStringNotContainsString('Data Milik Owner', $index->getContent());

        $this->actingAs($otherUser)->get(route('potensi.show', $potensi))->assertNotFound();
        $this->actingAs($otherUser)->get(route('potensi.edit', $potensi))->assertNotFound();
        $this->actingAs($otherUser)->get(route('potensi.sp1', $potensi))->assertNotFound();
        $this->actingAs($otherUser)->put(route('potensi.update', $potensi), [
            'tanggal_input' => '2026-08-31',
            'nama_usaha' => 'Percobaan Perubahan',
            'segmen' => 'PU',
            'uraian' => 'Tidak boleh berubah',
            'alamat' => 'Jl. Lain',
            'estimasi_tk' => 1,
            'estimasi_upah' => 1,
            'estimasi_iuran' => 1,
        ])->assertNotFound();
        $this->actingAs($otherUser)->delete(route('potensi.destroy', $potensi))->assertNotFound();

        $this->assertDatabaseHas('potensi', [
            'id' => $potensi->id,
            'user_id' => $owner->id,
            'nama_usaha' => 'Data Milik Owner',
        ]);
    }

    public function test_export_only_contains_the_authenticated_users_potensi(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        Potensi::create([
            'user_id' => $owner->id,
            'tanggal_input' => '2026-08-31',
            'nama_usaha' => 'Data Export Privat',
            'segmen' => 'PU',
            'uraian' => 'Data privat',
            'alamat' => 'Jl. Export No. 1',
            'estimasi_tk' => 5,
            'estimasi_upah' => 5000000,
            'estimasi_iuran' => 500000,
        ]);

        $response = $this->actingAs($otherUser)->get(route('potensi.export'));

        $response->assertOk();
        $this->assertStringNotContainsString('Data Export Privat', $response->getContent());
    }

    public function test_authenticated_user_can_download_the_import_template(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('potensi.template'));

        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'attachment; filename=Template_Import_Potensi_KSI.xlsx');
        $this->assertStringContainsString(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('Content-Type')
        );
        $this->assertStringStartsWith('PK', $response->streamedContent());
    }

    public function test_authenticated_user_can_import_rows_without_npwp(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('potensi.import.store'), [
            'rows' => [
                [
                    'tanggal_input' => '2026-09-10',
                    'nama_usaha' => 'Usaha Import Satu',
                    'npwp' => '',
                    'segmen' => 'PU',
                    'uraian' => 'Uraian satu',
                    'alamat' => 'Alamat satu',
                    'estimasi_tk' => 10,
                    'estimasi_upah' => 1000000,
                    'estimasi_iuran' => 100000,
                    'programs' => ['JKP'],
                ],
                [
                    'tanggal_input' => '2026-09-10',
                    'nama_usaha' => 'Usaha Import Dua',
                    'npwp' => '',
                    'segmen' => 'BPU',
                    'uraian' => 'Uraian dua',
                    'alamat' => 'Alamat dua',
                    'estimasi_tk' => 5,
                    'estimasi_upah' => 500000,
                    'estimasi_iuran' => 50000,
                    'programs' => ['JKK', 'JKP'],
                ],
            ],
        ]);

        $response->assertOk()->assertJson(['created' => 2, 'updated' => 0, 'errors' => []]);
        $this->assertDatabaseCount('potensi', 2);
        $this->assertDatabaseHas('potensi', ['nama_usaha' => 'Usaha Import Satu', 'user_id' => $user->id]);
        $this->assertDatabaseHas('potensi', ['nama_usaha' => 'Usaha Import Dua', 'user_id' => $user->id]);
        $this->assertDatabaseHas('program_potensi', ['jenis_program' => 'JKP']);
    }
}
