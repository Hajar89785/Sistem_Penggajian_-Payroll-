<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PayrollExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    protected $period_id;
    protected $rowNumber = 0;
    
    public function __construct($period_id)
    {
        $this->period_id = $period_id;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function collection()
    {
        $payrolls = Payroll::with(['employee', 'payrollPeriod'])->whereIn('status', ['Paid', 'Final']);
        if ($this->period_id) {
            $payrolls->where('payroll_period_id', $this->period_id);
        }
        return $payrolls->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'Periode',
            'NIK',
            'Nama Karyawan',
            'Gaji Pokok',
            'Total Tunjangan',
            'Total Potongan',
            'Penerimaan Bersih',
            'Tanggal Cetak',
        ];
    }

    public function map($payroll): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $payroll->payrollPeriod->name ?? '-',
            $payroll->employee->employee_code ?? '-',
            $payroll->employee->full_name ?? '-',
            $payroll->basic_salary,
            $payroll->total_allowances,
            $payroll->total_deductions,
            $payroll->net_salary,
            date('d M Y'),
        ];
    }

    public function columnFormats(): array
    {
        // Format Accounting Rupiah
        $accountingFormat = '_-"Rp"* #,##0_-;-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-';
        
        return [
            'E' => $accountingFormat,
            'F' => $accountingFormat,
            'G' => $accountingFormat,
            'H' => $accountingFormat,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row (Row 6)
            6 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FF000000'], 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF92D050'], // Hijau Daun
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn(); // Usually I

                // --- 1. Blok Judul Atas (Baris 2-4) ---
                $sheet->mergeCells('A2:I4');
                $sheet->setCellValue('A2', 'LAPORAN PENGGAJIAN KARYAWAN');
                $sheet->getStyle('A2:I4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF000000'],
                        'size' => 14
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFED7D31'], // Oranye Terang
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // --- 2. Format Data Tabel (Mulai baris 7) ---
                if ($highestRow >= 7) {
                    // Border data cells
                    $sheet->getStyle('A7:' . $highestColumn . $highestRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);

                    // Text alignments
                    $sheet->getStyle('A7:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // NO
                    $sheet->getStyle('C7:C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // NIK
                    $sheet->getStyle('I7:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tgl Cetak
                    $sheet->getStyle('B7:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Periode
                    $sheet->getStyle('D7:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Nama
                    
                    // --- 3. Baris Total Keseluruhan (Paling Bawah) ---
                    $totalRow = $highestRow + 1;
                    $sheet->mergeCells('A' . $totalRow . ':D' . $totalRow);
                    $sheet->setCellValue('A' . $totalRow, 'TOTAL KESELURUHAN');
                    
                    $sheet->setCellValue('E' . $totalRow, '=SUM(E7:E' . $highestRow . ')');
                    $sheet->setCellValue('F' . $totalRow, '=SUM(F7:F' . $highestRow . ')');
                    $sheet->setCellValue('G' . $totalRow, '=SUM(G7:G' . $highestRow . ')');
                    $sheet->setCellValue('H' . $totalRow, '=SUM(H7:H' . $highestRow . ')');

                    // Style the total row
                    $sheet->getStyle('A' . $totalRow . ':I' . $totalRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);
                    $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('A' . $totalRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                }

                // --- Pastikan perhitungan rumus otomatis jalan saat dibuka ---
                // Mengaktifkan calcMode otomatis pada worksheet agar formula terhitung
                $sheet->getParent()->getCalculationEngine()->setCalculationCacheEnabled(false);
            },
        ];
    }
}
