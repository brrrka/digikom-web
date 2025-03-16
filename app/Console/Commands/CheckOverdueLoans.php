<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckOverdueLoans extends Command
{
    protected $signature = 'loans:check-overdue';
    protected $description = 'Update status peminjaman yang sudah melewati tanggal selesai menjadi jatuh tenggat';

    public function handle()
    {
        $today = Carbon::now();

        // Ambil semua peminjaman yang sudah melewati tanggal selesai dan belum dikembalikan
        $overdueLoans = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_selesai', '<', $today)
            ->get();

        foreach ($overdueLoans as $loan) {
            $loan->status = 'jatuh tenggat';
            $loan->save();

            Log::info("Peminjaman ID {$loan->id} telah jatuh tenggat.");
        }

        $this->info('Pengecekan jatuh tenggat selesai.');
    }
}
