<?php

namespace App\Exports;

use App\Models\Potensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// Implements Maatwebsite interfaces so it can be passed directly to Excel::download
class PotensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $potensis;

    public function __construct(Collection $potensis)
    {
        $this->potensis = $potensis;
    }

    public function collection(): Collection
    {
        return $this->potensis;
    }

    public function headings(): array
    {
        return [
            'ID', 'Nama Usaha', 'Segmen', 'Program', 'Estimasi TK', 'Estimasi Iuran', 'Alamat', 'Tanggal Input'
        ];
    }

    public function map($potensi): array
    {
        $programs = $potensi->programPotensi->pluck('jenis_program')->join(', ');

        return [
            $potensi->id,
            $potensi->nama_usaha,
            $potensi->segmen,
            $programs,
            $potensi->estimasi_tk,
            $potensi->estimasi_iuran,
            $potensi->alamat,
            optional($potensi->tanggal_input)->format('Y-m-d'),
        ];
    }
}
