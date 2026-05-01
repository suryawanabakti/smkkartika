<?php

namespace App\Console\Commands;

use App\Models\PersonnelAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPersonnelRecapEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send-recap {--month=} {--year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email rekap kehadiran personel bulanan ke Kepala Sekolah';

    /**
     * Execute the console command.     
     */
    public function handle()
    {
        $email = config('services.kepalasekolah.email');

        if (empty($email)) {
            $this->error('Gagal: KEPALASEKOLAH_EMAIL belum disetting di file .env!');
            return self::FAILURE;
        }

        // Jika tidak diset, gunakan bulan dan tahun saat ini
        $month = $this->option('month') ?? date('n');
        $year = $this->option('year') ?? date('Y');

        $this->info("Menyiapkan rekap data kehadiran bulan {$month} tahun {$year}...");

        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        $personnel = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['admin', 'teacher', 'staff']);
        })->get();

        $attendanceData = PersonnelAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['user_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        $this->info("Mengirim email beserta lampiran Excel ke: {$email}...");

        try {
            Mail::to($email)->send(new \App\Mail\PersonnelAttendanceRecapMail(
                $month,
                $year,
                $personnel,
                $attendanceData,
                $daysInMonth,
                $startDate
            ));

            $this->info('Email rekap kehadiran berhasil dikirim!');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Gagal mengirim email: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
