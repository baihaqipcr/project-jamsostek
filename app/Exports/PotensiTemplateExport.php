<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PotensiTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    private const BRAND_GREEN = '0E7C66';

    /**
     * Sample/example row fill.
     */
    private const SAMPLE_FILL = 'EAF7F2';

    public function array(): array
    {
        // One marked example row so users see the expected data shape.
        return [
            [
                '1234567890123452',
                '2026-09-09',
                'PT Contoh Usaha',
                'PU',
                'Contoh uraian potensi calon peserta.',
                'Jl. Merdeka No. 1, Jakarta',
                '-6.2000000',
                '106.8500000',
                25,
                25000000,
                2500000,
                'JKK, JHT',
                'Belum dihubungi',
                'CONTOH - hapus baris ini sebelum upload.',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NPWP',
            'Tanggal Input',
            'Nama Usaha',
            'Segmen',
            'Uraian',
            'Alamat',
            'Latitude',
            'Longitude',
            'Estimasi TK',
            'Estimasi Upah',
            'Estimasi Iuran',
            'Program',
            'Status Tindak Lanjut',
            'Catatan',
        ];
    }

    public function columnWidths(): array
    {
        // Sensible defaults; AfterSheet refines them by measuring actual content.
        return [
            'A' => 20,
            'B' => 14,
            'C' => 28,
            'D' => 12,
            'E' => 40,
            'F' => 36,
            'G' => 12,
            'H' => 12,
            'I' => 14,
            'J' => 16,
            'K' => 16,
            'L' => 20,
            'M' => 22,
            'N' => 32,
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        // Header row: bold, white text on BPJS green, centered, with padding.
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
            // Example row readability.
            2 => [
                'font' => [
                    'italic' => true,
                    'color' => ['rgb' => '5B6B63'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => self::SAMPLE_FILL],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Freeze the header row so it stays visible while scrolling.
                $sheet->freezePane('A2');

                $this->applyColumnAutoWidths($sheet);
                $this->applyDataValidations($sheet);
            },
        ];
    }

    /**
     * Measure header + sample content and fit each column width, clamped.
     */
    private function applyColumnAutoWidths(Worksheet $sheet): void
    {
        $lastColumn = 'N';
        $highestRow = max($sheet->getHighestRow(), 2);

        foreach (range('A', $lastColumn) as $column) {
            $maxLength = 0;

            for ($row = 1; $row <= $highestRow; $row++) {
                $value = $sheet->getCell($column.$row)->getValue();
                if ($value === null || $value === '') {
                    continue;
                }

                $maxLength = max($maxLength, mb_strlen((string) $value));
            }

            // 3 chars padding, clamp between 10 and 45 for a tidy sheet.
            $width = max(10, min($maxLength + 3, 45));
            $sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function applyDataValidations(Worksheet $sheet): void
    {
        $lastRow = 1000;

        $segmenValidation = $this->makeListValidation('PU,BPU,Jakon', 'Segmen', 'Pilih salah satu: PU, BPU, Jakon');
        $sheet->setDataValidation("D2:D{$lastRow}", $segmenValidation);

        $statusValidation = $this->makeListValidation(
            'Belum dihubungi,Sudah dihubungi,Jadi peserta,Ditolak',
            'Status Tindak Lanjut',
            'Pilih salah satu: Belum dihubungi, Sudah dihubungi, Jadi peserta, Ditolak'
        );
        $sheet->setDataValidation("M2:M{$lastRow}", $statusValidation);
    }

    private function makeListValidation(string $formula, string $title, string $prompt): DataValidation
    {
        $validation = new DataValidation();

        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(false); // OOXML inverts this; false shows the arrow.
        $validation->setErrorTitle('Input tidak valid');
        $validation->setError("Pilih salah satu dari daftar.");
        $validation->setPromptTitle($title);
        $validation->setPrompt($prompt);
        $validation->setFormula1('"'.$formula.'"');

        return $validation;
    }
}
