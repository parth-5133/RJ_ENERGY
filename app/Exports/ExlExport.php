<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Contracts\View\View;

class ExlExport implements FromView, WithColumnWidths
{
    protected $data;
    protected $days;
    protected $monthName;
    protected $year;

    public function __construct(array $data, array $days, string $monthName, int $year)
    {
        $this->data = $data;
        $this->days = $days;
        $this->monthName = $monthName;
        $this->year = $year;
    }

    public function view(): View
    {
        return view('exports.attendance_report', [
            'data' => $this->data,
            'days' => $this->days,
            'monthName' => $this->monthName,
            'year' => $this->year,
            'colSpan' => count($this->days) + 1,
        ]);
    }

    public function columnWidths(): array
    {
        // Define column widths (A, B, C, etc.)
        return [
            'A' => 25,  // First column width
            'B' => 15,  // Second column width
            // Add more columns as needed
        ];
    }
}

