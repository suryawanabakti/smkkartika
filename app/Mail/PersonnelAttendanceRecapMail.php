<?php

namespace App\Mail;

use App\Exports\PersonnelAttendanceExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class PersonnelAttendanceRecapMail extends Mailable
{
    use Queueable, SerializesModels;

    public $month;
    public $year;
    public $personnel;
    public $attendanceData;
    public $daysInMonth;
    public $startDate;

    /**
     * Create a new message instance.
     */
    public function __construct($month, $year, $personnel, $attendanceData, $daysInMonth, $startDate)
    {
        $this->month = $month;
        $this->year = $year;
        $this->personnel = $personnel;
        $this->attendanceData = $attendanceData;
        $this->daysInMonth = $daysInMonth;
        $this->startDate = $startDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $monthName = date('F', mktime(0, 0, 0, $this->month, 1));
        return new Envelope(
            subject: "Rekap Kehadiran Pegawai - {$monthName} {$this->year}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.personnel_recap',
            with: [
                'monthName' => date('F', mktime(0, 0, 0, $this->month, 1)),
                'year' => $this->year,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => Excel::raw(new PersonnelAttendanceExport(
                $this->personnel,
                $this->attendanceData,
                $this->month,
                $this->year,
                $this->daysInMonth,
                $this->startDate
            ), \Maatwebsite\Excel\Excel::XLSX), 'rekap-kehadiran-pegawai-' . $this->year . '-' . $this->month . '.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
