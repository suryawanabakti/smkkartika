<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PersonnelAttendanceExport implements FromView, ShouldAutoSize
{
    protected $personnel;
    protected $attendanceData;
    protected $month;
    protected $year;
    protected $daysInMonth;
    protected $startDate;

    public function __construct($personnel, $attendanceData, $month, $year, $daysInMonth, $startDate)
    {
        $this->personnel = $personnel;
        $this->attendanceData = $attendanceData;
        $this->month = $month;
        $this->year = $year;
        $this->daysInMonth = $daysInMonth;
        $this->startDate = $startDate;
    }

    public function view(): View
    {
        return view('admin.attendance.exports.personnel_recap', [
            'personnel' => $this->personnel,
            'attendanceData' => $this->attendanceData,
            'month' => $this->month,
            'year' => $this->year,
            'daysInMonth' => $this->daysInMonth,
            'startDate' => $this->startDate,
        ]);
    }
}
