<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class ApprovedTransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $transactions;
    protected $selectedColumns;
    protected $allColumns;

    public function __construct(array $transactions, array $selectedColumns)
    {
        $this->transactions = $transactions;
        $this->selectedColumns = $selectedColumns;
        
        $this->allColumns = [
            'first_name'        => 'Nama Depan',
            'emp_code'          => 'Kode Karyawan',
            'last_name'         => 'Nama Belakang',
            'department'        => 'Departemen',
            'position'          => 'Posisi',
            'punch_time'        => 'Waktu Absen',
            'punch_state_display' => 'Status Absen',
            'verify_type_display' => 'Tipe Verifikasi',
            'gps_location'      => 'Lokasi GPS',
            'work_code'         => 'Kode Kerja',
            'terminal_alias'    => 'Alias Terminal',
            'temperature'       => 'Suhu',
            'is_mask'           => 'Memakai Masker',
            'upload_time'       => 'Waktu Upload',
        ];
    }

    public function collection()
    {
        return collect($this->transactions);
    }

    public function headings(): array
    {
        return collect($this->allColumns)
            ->filter(fn ($value, $key) => in_array($key, $this->selectedColumns))
            ->values()
            ->toArray();
    }

    /**
     * @var mixed $transaction
     */
    public function map($transaction): array
    {
        // 1. Siapkan semua kemungkinan data dalam sebuah array asosiatif
        $fullRowData = [
            'first_name'        => $transaction['first_name'] ?? '-',
            'emp_code'          => $transaction['emp_code'] ?? '-',
            'last_name'         => $transaction['last_name'] ?? '-',
            'department'        => $transaction['department'] ?? '-', // Ini sudah benar
            'position'          => $transaction['position'] ?? '-',   // Ini sudah benar
            'punch_time'        => isset($transaction['punch_time']) ? Carbon::parse($transaction['punch_time'])->format('d-m-Y H:i:s') : '-',
            'punch_state_display' => $transaction['punch_state_display'] ?? '-',
            'verify_type_display' => $transaction['verify_type_display'] ?? '-',
            'work_code'         => $transaction['work_code'] ?? '-',
            'terminal_alias'    => $transaction['terminal_alias'] ?? '-',
            'gps_location'      => $transaction['gps_location'] ?? '-',
            'temperature'       => $transaction['temperature'] ?? '-',
            'is_mask'           => $transaction['is_mask'] ?? '-',
            'upload_time'       => isset($transaction['upload_time']) ? Carbon::parse($transaction['upload_time'])->format('d-m-Y H:i:s') : '-',
        ];

        // 2. Bangun baris data HANYA berdasarkan urutan master $allColumns
        $mappedRow = [];
        foreach ($this->allColumns as $columnKey => $columnHeading) {
            if (in_array($columnKey, $this->selectedColumns)) {
                $mappedRow[] = $fullRowData[$columnKey] ?? '-';
            }
        }
        
        return $mappedRow;
    }
}

