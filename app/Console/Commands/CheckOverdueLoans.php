<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckOverdueLoans extends Command
{
    protected $signature = 'loans:check-overdue
                            {--batch-size=100 : Number of loans to process at once}
                            {--dry-run : Show what would be updated without making changes}';

    protected $description = 'Update status peminjaman yang sudah melewati tanggal selesai menjadi jatuh tenggat';

    public function handle()
    {
        $startTime = microtime(true);
        $batchSize = (int) $this->option('batch-size');
        $isDryRun = $this->option('dry-run');

        $this->info('Memulai pengecekan peminjaman jatuh tenggat...');

        if ($isDryRun) {
            $this->warn('MODE DRY RUN - Tidak ada perubahan yang akan disimpan');
        }

        try {
            $today = Carbon::now();
            $totalUpdated = 0;

            // Query dengan batch processing untuk performa optimal
            $query = Peminjaman::where('status', 'dipinjam')
                ->where('tanggal_selesai', '<', $today)
                ->with('user:id,name,nim') // Eager load user data for logging
                ->orderBy('tanggal_selesai'); // Process oldest first

            // Get total count for progress bar
            $totalCount = $query->count();

            if ($totalCount === 0) {
                $this->info('Tidak ada peminjaman yang jatuh tenggat.');
                return;
            }

            $this->info("Ditemukan {$totalCount} peminjaman yang jatuh tenggat.");

            // Create progress bar
            $progressBar = $this->output->createProgressBar($totalCount);
            $progressBar->start();

            // Process in batches to avoid memory issues
            $query->chunk($batchSize, function ($overdueLoans) use (&$totalUpdated, $progressBar, $isDryRun) {

                if (!$isDryRun) {
                    DB::beginTransaction();
                }

                try {
                    foreach ($overdueLoans as $loan) {
                        $daysOverdue = Carbon::parse($loan->tanggal_selesai)->diffInDays(Carbon::now());

                        if (!$isDryRun) {
                            // Update status
                            $loan->status = 'jatuh tenggat';
                            $loan->save();
                        }

                        // Log individual loan update
                        $logData = [
                            'peminjaman_id' => $loan->id,
                            'user_name' => $loan->user->name ?? 'Unknown',
                            'user_nim' => $loan->user->nim ?? 'Unknown',
                            'tanggal_selesai' => $loan->tanggal_selesai->format('Y-m-d'),
                            'days_overdue' => $daysOverdue,
                            'dry_run' => $isDryRun
                        ];

                        Log::info("Peminjaman ID {$loan->id} " . ($isDryRun ? 'akan diubah' : 'telah diubah') . " menjadi jatuh tenggat", $logData);

                        $totalUpdated++;
                        $progressBar->advance();
                    }

                    if (!$isDryRun) {
                        DB::commit();
                    }
                } catch (\Exception $e) {
                    if (!$isDryRun) {
                        DB::rollBack();
                    }

                    Log::error('Error processing overdue loans batch: ' . $e->getMessage());
                    $this->error('Error processing batch: ' . $e->getMessage());

                    // Continue with next batch instead of stopping completely
                }
            });

            $progressBar->finish();
            $this->newLine();

            $executionTime = round(microtime(true) - $startTime, 2);

            // Summary
            $action = $isDryRun ? 'akan diupdate' : 'berhasil diupdate';
            $this->info("Selesai! {$totalUpdated} peminjaman {$action} ke status 'jatuh tenggat'");
            $this->info("Waktu eksekusi: {$executionTime} detik");

            // Memory usage info
            $memoryUsage = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
            $this->info("Penggunaan memori puncak: {$memoryUsage} MB");

            // Log summary
            Log::info('Overdue loans check completed', [
                'total_processed' => $totalUpdated,
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage . 'MB',
                'batch_size' => $batchSize,
                'dry_run' => $isDryRun
            ]);
        } catch (\Exception $e) {
            $executionTime = round(microtime(true) - $startTime, 2);

            Log::error('Error in overdue loans check: ' . $e->getMessage(), [
                'execution_time' => $executionTime,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return 1; // Return error code
        }

        return 0; // Success
    }

    /**
     * Get statistics about overdue loans without updating
     */
    public function getOverdueStats()
    {
        $today = Carbon::now();

        $stats = DB::table('peminjamans')
            ->select([
                DB::raw('COUNT(*) as total_overdue'),
                DB::raw('AVG(DATEDIFF(NOW(), tanggal_selesai)) as avg_days_overdue'),
                DB::raw('MAX(DATEDIFF(NOW(), tanggal_selesai)) as max_days_overdue'),
                DB::raw('MIN(DATEDIFF(NOW(), tanggal_selesai)) as min_days_overdue')
            ])
            ->where('status', 'dipinjam')
            ->where('tanggal_selesai', '<', $today)
            ->first();

        return [
            'total_overdue' => $stats->total_overdue ?? 0,
            'avg_days_overdue' => round($stats->avg_days_overdue ?? 0, 1),
            'max_days_overdue' => $stats->max_days_overdue ?? 0,
            'min_days_overdue' => $stats->min_days_overdue ?? 0
        ];
    }
}
