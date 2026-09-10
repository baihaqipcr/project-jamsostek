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

    public function array(): array
    {
        return [];
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

    public function columnWidths(): array
    {
        // Sensible defaults; AfterSheet refines them by measuring actual content.
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
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Freeze the header row so it stays visible while scrolling.
                $sheet->freezePane('A2');

                $sheet->getRowDimension(1)->setRowHeight(30);
                $this->applyColumnAutoWidths($sheet);
                $this->applyDataValidations($sheet);
                $this->addInstructions($sheet);
            },
        ];
    }

    /**
     * Measure header + sample content and fit each column width, clamped.
     */
    private function applyColumnAutoWidths(Worksheet $sheet): void
    {
        $lastColumn = 'N';
        $highestRow = max($sheet->getHighestRow(), 1);

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

        $segmenValidation = $this->makeListValidation('PU,BPU,Jakon', 'Segmen', 'Pilih salah satu: PU, BPU, Jakon', true);
        $sheet->setDataValidation("D2:D{$lastRow}", $segmenValidation);

        $statusValidation = $this->makeListValidation(
            'Belum dihubungi,Sudah dihubungi,Jadi peserta,Ditolak',
            'Status Tindak Lanjut',
            'Pilih salah satu: Belum dihubungi, Sudah dihubungi, Jadi peserta, Ditolak',
            true
        );
        $sheet->setDataValidation("M2:M{$lastRow}", $statusValidation);
    }

    private function makeListValidation(string $formula, string $title, string $prompt, bool $allowBlank = false): DataValidation
    {
        $validation = new DataValidation();

        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank($allowBlank);
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

    private function addInstructions(Worksheet $sheet): void
    {
        $notes = [
            'A1' => 'Wajib diisi dengan format YYYY-MM-DD.',
            'B1' => 'Wajib diisi.',
            'C1' => 'Opsional. Isi 15 digit angka jika tersedia.',
            'D1' => 'Opsional. Pilih PU, BPU, atau Jakon.',
            'E1' => 'Jelaskan bidang usaha atau uraian potensi.',
            'F1' => 'Wajib diisi.',
            'G1' => 'Opsional. Kosongkan jika belum survei lokasi.',
            'H1' => 'Opsional. Kosongkan jika belum survei lokasi.',
            'I1' => 'Isi dengan angka jumlah tenaga kerja.',
            'J1' => 'Isi dengan angka tanpa simbol mata uang.',
            'K1' => 'Isi dengan angka tanpa simbol mata uang.',
            'L1' => 'Opsional. Pisahkan program dengan koma, misalnya JKK, JHT.',
            'M1' => 'Opsional. Pilih status tindak lanjut.',
            'N1' => 'Opsional. Tambahkan catatan jika diperlukan.',
        ];

        foreach ($notes as $cell => $text) {
            $sheet->getComment($cell)->getText()->createTextRun($text);
        }
    }
}
