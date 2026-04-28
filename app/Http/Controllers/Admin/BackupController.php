<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index()
    {
        $backups = [];
        if (Storage::exists('backups')) {
            $files = Storage::files('backups');
            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => $this->formatBytes(Storage::size($file)),
                    'date' => Carbon::createFromTimestamp(Storage::lastModified($file))->format('d M Y, H:i:s'),
                    'raw_date' => Storage::lastModified($file)
                ];
            }
        }
        
        // Sort by date descending
        usort($backups, function($a, $b) {
            return $b['raw_date'] <=> $a['raw_date'];
        });

        return view('admin.settings.backup', compact('backups'));
    }

    public function create()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
        
        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        $path = Storage::path('backups/' . $filename);

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password') ? '"' . config('database.connections.mysql.password') . '"' : '""',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            '"' . $path . '"'
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Gagal membuat backup database.');
        }

        return back()->with('success', 'Backup database berhasil dibuat: ' . $filename);
    }

    public function download($filename)
    {
        $path = 'backups/' . $filename;
        if (Storage::exists($path)) {
            return Storage::download($path);
        }
        return back()->with('error', 'File tidak ditemukan.');
    }

    public function destroy($filename)
    {
        $path = 'backups/' . $filename;
        if (Storage::exists($path)) {
            Storage::delete($path);
            return back()->with('success', 'Backup berhasil dihapus.');
        }
        return back()->with('error', 'File tidak ditemukan.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
