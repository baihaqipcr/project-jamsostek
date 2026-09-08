<?php

namespace App\Http\Controllers;

use App\Exports\PotensiExport;
use App\Http\Requests\StorePotensiRequest;
use App\Models\Potensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PotensiController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Potensi::query()
            ->with(['user', 'programPotensi'])
            ->latest();

        if ($request->filled('segmen')) {
            $query->where('segmen', $request->string('segmen'));
        }

        if ($request->filled('status')) {
            $query->where('status_tindak_lanjut', $request->string('status'));
        }

        return Inertia::render('Potensi/Index', [
            'potensis' => $query->paginate(10)->withQueryString(),
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

    public function show(Potensi $potensi): Response
    {
        $potensi->load(['user', 'programPotensi']);

        return Inertia::render('Potensi/Show', [
            'potensi' => $potensi,
        ]);
    }

    public function edit(Potensi $potensi): Response
    {
        $potensi->load('programPotensi');

        return Inertia::render('Potensi/Edit', [
            'potensi' => $potensi,
        ]);
    }

    public function update(StorePotensiRequest $request, Potensi $potensi): RedirectResponse
    {
        $potensi->update($request->validated());
        $this->syncProgramPotensi($potensi, $request->input('programs', []));

        return redirect()->route('potensi.show', $potensi)->with('success', 'Potensi berhasil diperbarui.');
    }

    public function destroy(Potensi $potensi): RedirectResponse
    {
        $potensi->delete();

        return redirect()->route('potensi.index')->with('success', 'Potensi berhasil dihapus.');
    }

    public function downloadSp1(Potensi $potensi)
    {
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
        $potensis = Potensi::with(['programPotensi', 'user'])->get();

        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new PotensiExport($potensis), 'potensi.xlsx');
        }

        $headings = ['ID', 'Nama Usaha', 'Segmen', 'Program', 'Estimasi TK', 'Estimasi Iuran', 'Alamat', 'Tanggal Input'];

        $lines = [];
        $lines[] = implode(',', array_map(fn ($h) => '"'.str_replace('"', '""', $h).'"', $headings));

        foreach ($potensis as $p) {
            $programs = $p->programPotensi->pluck('jenis_program')->join(', ');
            $row = [
                $p->id,
                $p->nama_usaha,
                $p->segmen,
                $programs,
                $p->estimasi_tk,
                $p->estimasi_iuran,
                $p->alamat,
                optional($p->tanggal_input)->format('Y-m-d'),
            ];

            $lines[] = implode(',', array_map(fn ($c) => '"'.str_replace('"', '""', (string) $c).'"', $row));
        }

        $content = implode("\n", $lines);

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="potensi.xlsx"',
        ]);
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
}
