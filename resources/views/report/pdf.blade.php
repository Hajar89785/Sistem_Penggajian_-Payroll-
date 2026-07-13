<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penggajian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            margin-top: 20px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $setting->app_name ?? 'Laporan Penggajian' }}</h2>
        @if($period)
            <p>Rekapitulasi Gaji Karyawan - Periode {{ $period->name }}</p>
        @else
            <p>Agregat Pengeluaran Gaji - Semua Periode</p>
        @endif
    </div>

    @if($period)
        <!-- Single Period -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Karyawan</th>
                    <th width="15%">Gaji Pokok</th>
                    <th width="15%">Tunjangan</th>
                    <th width="15%">Potongan</th>
                    <th width="15%">Gaji Bersih</th>
                    <th width="15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $row->employee->full_name }}</strong><br>
                        <span style="font-size:10px; color:#666;">{{ $row->employee->employee_code }}</span>
                    </td>
                    <td class="text-right">Rp {{ number_format($row->basic_salary, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_allowances, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_deductions, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->net_salary, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data penggajian.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">TOTAL KESELURUHAN:</th>
                    <th class="text-right">Rp {{ number_format($summary->total_basic_salary ?? 0, 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($summary->total_allowances ?? 0, 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($summary->total_deductions ?? 0, 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($summary->total_net_salary ?? 0, 0, ',', '.') }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <div class="summary-box">
            <table style="width: 50%; float: right;">
                <tr>
                    <td><strong>Total Karyawan Digaji:</strong></td>
                    <td class="text-right">{{ $summary->total_employees ?? 0 }} Orang</td>
                </tr>
                <tr>
                    <td><strong>Total Pengeluaran (Net):</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($summary->total_net_salary ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

    @else
        <!-- All Periods Aggregation -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Periode</th>
                    <th width="15%">Jml Karyawan</th>
                    <th width="15%">Gaji Pokok</th>
                    <th width="15%">Tunjangan</th>
                    <th width="15%">Potongan</th>
                    <th width="15%">Pengeluaran Net</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summary as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->payrollPeriod->name }}</td>
                    <td class="text-center">{{ $row->total_employees }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_basic_salary, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_allowances, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_deductions, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_net_salary, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data agregat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif

</body>
</html>
