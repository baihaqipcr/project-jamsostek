<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PotensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents, WithColumnFormatting
{
    private const BRAND_GREEN = '0E7C66';

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
        ];
    }

    public function map($potensi): array
    {
        $tanggalInput = $potensi->tanggal_input;

        return [
            $tanggalInput ? Date::dateTimeToExcel($tanggalInput) : '',
            $potensi->nama_usaha ?? '',
            $potensi->npwp ?? '',
            $potensi->segmen ?? '',
            $potensi->uraian ?? '',
            $potensi->alamat ?? '',
            $potensi->latitude !== null ? (float) $potensi->latitude : '',
            $potensi->longitude !== null ? (float) $potensi->longitude : '',
            $potensi->estimasi_tk ?? '',
            $potensi->estimasi_upah !== null ? (float) $potensi->estimasi_upah : '',
            $potensi->estimasi_iuran !== null ? (float) $potensi->estimasi_iuran : '',
            $potensi->programPotensi->pluck('jenis_program')->filter()->join(', '),
            $potensi->status_tindak_lanjut ?? '',
            $potensi->catatan ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => self::BRAND_GREEN],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 30,
            'C' => 20,
            'D' => 12,
            'E' => 40,
            'F' => 38,
            'G' => 14,
            'H' => 14,
            'I' => 22,
            'J' => 16,
            'K' => 16,
            'L' => 30,
            'M' => 22,
            'N' => 32,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'yyyy-mm-dd',
            'G' => '0.0000000',
            'H' => '0.0000000',
            'I' => '0',
            'J' => '#,##0.00',
            'K' => '#,##0.00',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                $sheet->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}
