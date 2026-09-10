<?php

namespace App\Http\Controllers;

use App\Exports\PotensiExport;
use App\Exports\PotensiTemplateExport;
use App\Http\Requests\StorePotensiRequest;
use App\Models\Potensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PotensiController extends Controller
{
    public function index(Request $request): Response
    {
        $baseQuery = Potensi::where('user_id', Auth::id());
        $query = clone $baseQuery;

        if ($request->filled('segmen')) {
            $query->where('segmen', $request->string('segmen'));
        }

        if ($request->filled('status')) {
            $query->where('status_tindak_lanjut', $request->string('status'));
        }

        $filteredAggregates = (clone $query)
            ->selectRaw("COUNT(*) as total_count, SUM(CASE WHEN status_tindak_lanjut = 'Jadi peserta' THEN 1 ELSE 0 END) as active_count, COALESCE(SUM(estimasi_iuran), 0) as total_iuran")
            ->first();
        $allAggregates = $baseQuery
            ->selectRaw("COUNT(*) as total_count, SUM(CASE WHEN status_tindak_lanjut = 'Jadi peserta' THEN 1 ELSE 0 END) as active_count, COALESCE(SUM(estimasi_iuran), 0) as total_iuran")
            ->first();

        $query->with(['user', 'programPotensi'])->latest();

        return Inertia::render('Potensi/Index', [
            'potensis' => $query->paginate(10)->withQueryString(),
            'aggregates' => [
                'total_count_all' => (int) $allAggregates->total_count,
                'active_count_all' => (int) $allAggregates->active_count,
                'total_iuran_all' => (float) $allAggregates->total_iuran,
                'total_count_filtered' => (int) $filteredAggregates->total_count,
                'active_count_filtered' => (int) $filteredAggregates->active_count,
                'total_iuran_filtered' => (float) $filteredAggregates->total_iuran,
            ],
            'filters' => [
                'segmen' => $request->string('segmen')->toString(),
                'status' => $request->string('status')->toString(),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Potensi/Create');
    }

    public function store(StorePotensiRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['status_tindak_lanjut'] = $validated['status_tindak_lanjut'] ?? 'Belum dihubungi';

        $potensi = Potensi::create($validated);
        $this->syncProgramPotensi($potensi, $request->input('programs', []));

        return redirect()->route('potensi.index')->with('success', 'Potensi berhasil disimpan.');
    }

    public function show(int $potensi): Response
    {
        $potensi = $this->ownedPotensi($potensi);
        $potensi->load(['user', 'programPotensi']);

        return Inertia::render('Potensi/Show', [
            'potensi' => $potensi,
        ]);
    }

    public function edit(int $potensi): Response
    {
        $potensi = $this->ownedPotensi($potensi);
        $potensi->load('programPotensi');

        return Inertia::render('Potensi/Edit', [
            'potensi' => $potensi,
        ]);
    }

    public function update(StorePotensiRequest $request, int $potensi): RedirectResponse
    {
        $potensi = $this->ownedPotensi($potensi);
        $potensi->update($request->validated());
        $this->syncProgramPotensi($potensi, $request->input('programs', []));

        return redirect()->route('potensi.show', $potensi)->with('success', 'Potensi berhasil diperbarui.');
    }

    public function destroy(int $potensi): RedirectResponse
    {
        $potensi = $this->ownedPotensi($potensi);
        $potensi->delete();

        return redirect()->route('potensi.index')->with('success', 'Potensi berhasil dihapus.');
    }

    public function downloadSp1(int $potensi)
    {
        $potensi = $this->ownedPotensi($potensi);
        $potensi->load(['user', 'programPotensi']);

        $potensi->update([
            'status_sp1' => true,
            'tanggal_cetak_sp1' => now(),
        ]);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = Pdf::loadView('potensi.sp1', compact('potensi'));

            return $pdf->download(sprintf('SP1_%s.pdf', $potensi->id));
        }

        $content = "%PDF-1.4\n%âãÏÓ\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 200 200] /Contents 4 0 R >>\nendobj\n4 0 obj\n<< /Length 44 >>\nstream\nBT /F1 12 Tf 10 100 Td (SP1: " . addslashes($potensi->nama_usaha) . ") Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000010 00000 n \n0000000053 00000 n \n0000000100 00000 n \n0000000200 00000 n \ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n%%EOF";

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="SP1_%s.pdf"', $potensi->id),
        ]);
    }

    public function export()
    {
        $potensis = Potensi::where('user_id', Auth::id())
            ->with(['programPotensi', 'user'])
            ->get();

        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new PotensiExport($potensis), 'Export_Potensi_KSI.xlsx');
        }

        $headings = [
            'Tanggal Input', 'Nama Usaha / Perusahaan', 'NPWP', 'Segmen',
            'Uraian / Bidang Usaha', 'Alamat Lengkap', 'Latitude', 'Longitude',
            'Estimasi Tenaga Kerja', 'Estimasi Upah', 'Estimasi Iuran',
            'Program JKK, JKM, JHT, JP, JKP', 'Status Tindak Lanjut', 'Catatan',
        ];

        $lines = [];
        $lines[] = implode(',', array_map(fn ($h) => '"'.str_replace('"', '""', $h).'"', $headings));

        foreach ($potensis as $p) {
            $programs = $p->programPotensi->pluck('jenis_program')->join(', ');
            $row = [
                optional($p->tanggal_input)->format('Y-m-d'),
                $p->nama_usaha ?? '',
                $p->npwp ?? '',
                $p->segmen ?? '',
                $p->uraian ?? '',
                $p->alamat ?? '',
                $p->latitude ?? '',
                $p->longitude ?? '',
                $p->estimasi_tk ?? '',
                $p->estimasi_upah ?? '',
                $p->estimasi_iuran ?? '',
                $programs,
                $p->status_tindak_lanjut ?? '',
                $p->catatan ?? '',
            ];

            $lines[] = implode(',', array_map(fn ($c) => '"'.str_replace('"', '""', (string) $c).'"', $row));
        }

        $content = implode("\n", $lines);

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Export_Potensi_KSI.xlsx"',
        ]);
    }

    public function importIndex(): Response
    {
        return Inertia::render('Potensi/Import');
    }

    public function downloadTemplate()
    {
        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new PotensiTemplateExport, 'Template_Import_Potensi_KSI.xlsx');
        }

        // Graceful fallback if the Excel package is unavailable.
        $headings = ['NPWP', 'Tanggal Input', 'Nama Usaha', 'Segmen', 'Uraian', 'Alamat', 'Latitude', 'Longitude', 'Estimasi TK', 'Estimasi Upah', 'Estimasi Iuran', 'Program', 'Status Tindak Lanjut', 'Catatan'];

        $content = implode(',', array_map(fn ($h) => '"'.str_replace('"', '""', $h).'"', $headings));

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Template_Import_Potensi_KSI.xlsx"',
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'rows' => ['required', 'array', 'min:1'],
        ]);

        $rows = $request->input('rows');
        $errors = [];
        $created = 0;
        $updated = 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                // +2: row 1 is the Excel header.
                $rowNumber = $index + 2;

                try {
                    $data = $this->validateImportRow($row);

                    $programs = $data['programs'] ?? [];
                    unset($data['programs']);

                    $data['user_id'] = Auth::id();
                    $data['status_tindak_lanjut'] = $data['status_tindak_lanjut'] ?? 'Belum dihubungi';

                    // Treat empty optional strings as null for nullable columns.
                    foreach (['latitude', 'longitude', 'catatan'] as $nullable) {
                        if (array_key_exists($nullable, $data) && $data[$nullable] === '') {
                            $data[$nullable] = null;
                        }
                    }

                    // Only records with an NPWP can be matched to an existing record.
                    $potensi = filled($data['npwp'] ?? null)
                        ? Potensi::updateOrCreate(
                            ['user_id' => Auth::id(), 'npwp' => $data['npwp']],
                            $data
                        )
                        : Potensi::create($data);

                    $this->syncProgramPotensi($potensi, $programs);

                    if ($potensi->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $updated++;
                    }
                } catch (ValidationException $e) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'message' => $e->validator->errors()->first(),
                    ];
                } catch (\Throwable $e) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Gagal menyimpan data: '.$e->getMessage(),
                    ];
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal memproses file: '.$e->getMessage(),
            ], 422);
        }

        return response()->json([
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
        ]);
    }

    protected function validateImportRow(array $row): array
    {
        $validator = Validator::make($row, [
            'npwp' => ['nullable', 'string', 'max:20'],
            'tanggal_input' => ['required', 'date'],
            'nama_usaha' => ['required', 'string', 'max:255'],
            'segmen' => ['required', 'in:PU,BPU,Jakon'],
            'uraian' => ['required', 'string'],
            'alamat' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'estimasi_tk' => ['required', 'integer', 'min:1'],
            'estimasi_upah' => ['required', 'numeric', 'min:0'],
            'estimasi_iuran' => ['required', 'numeric', 'min:0'],
            'programs' => ['nullable', 'array'],
            'programs.*' => ['string', 'in:JKK,JKM,JHT,JP,JKP'],
            'status_tindak_lanjut' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi.',
            'in' => ':attribute tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute tidak boleh lebih kecil dari :min.',
            'between' => ':attribute di luar rentang yang diizinkan.',
            'date' => ':attribute harus berupa tanggal.',
        ], [
            'npwp' => 'NPWP',
            'tanggal_input' => 'tanggal input',
            'nama_usaha' => 'nama usaha',
            'segmen' => 'segmen',
            'uraian' => 'uraian',
            'alamat' => 'alamat',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'estimasi_tk' => 'estimasi tenaga kerja',
            'estimasi_upah' => 'estimasi upah',
            'estimasi_iuran' => 'estimasi iuran',
            'status_tindak_lanjut' => 'status tindak lanjut',
            'catatan' => 'catatan',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // Normalize NPWP to 15 digits and enforce the exact length.
        if (filled($validated['npwp'] ?? null)) {
            $validated['npwp'] = preg_replace('/\D/', '', $validated['npwp']);

            $npwpValidator = Validator::make(['npwp' => $validated['npwp']], [
                'npwp' => ['required', 'digits:15'],
            ], [
                'digits' => 'NPWP harus 15 digit angka.',
            ]);

            if ($npwpValidator->fails()) {
                throw new ValidationException($npwpValidator);
            }
        }

        return $validated;
    }

    protected function syncProgramPotensi(Potensi $potensi, array $programs): void
    {
        $potensi->programPotensi()->delete();

        foreach ($programs as $program) {
            $potensi->programPotensi()->create([
                'jenis_program' => $program,
            ]);
        }
    }

    protected function ownedPotensi(int $id): Potensi
    {
        return Potensi::where('user_id', Auth::id())->findOrFail($id);
    }
}
