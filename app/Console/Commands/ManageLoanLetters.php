<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Services\LoanLetterService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ManageLoanLetters extends Command
{
    protected $signature = 'loans:manage-letters
                            {action : Action to perform (generate, regenerate, cleanup, stats)}
                            {--id= : Specific peminjaman ID}
                            {--status= : Filter by status (disetujui, dipinjam, etc)}
                            {--batch-size=50 : Number of letters to process at once}
                            {--force : Force regeneration even if letter exists}
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'Manage loan letters: generate, regenerate, cleanup orphaned files, or show statistics';

    protected $letterService;

    public function __construct(LoanLetterService $letterService)
    {
        parent::__construct();
        $this->letterService = $letterService;
    }

    public function handle()
    {
        $action = $this->argument('action');
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('MODE DRY RUN - Tidak ada perubahan yang akan disimpan');
        }

        switch ($action) {
            case 'generate':
                return $this->generateLetters();
            case 'regenerate':
                return $this->regenerateLetters();
            case 'cleanup':
                return $this->cleanupOrphanedFiles();
            case 'stats':
                return $this->showStatistics();
            default:
                $this->error("Action tidak valid: {$action}");
                $this->info('Available actions: generate, regenerate, cleanup, stats');
                return 1;
        }
    }

    /**
     * Generate letters for approved loans that don't have letters yet
     */
    private function generateLetters()
    {
        $this->info('Mencari peminjaman yang perlu di-generate suratnya...');

        $query = Peminjaman::where('status', 'disetujui')
            ->where(function ($q) {
                $q->whereNull('bukti_path')
                    ->orWhere('bukti_path', '');
            });

        if ($this->option('id')) {
            $query->where('id', $this->option('id'));
        }

        $peminjamans = $query->get();

        if ($peminjamans->isEmpty()) {
            $this->info('Tidak ada peminjaman yang perlu di-generate suratnya.');
            return 0;
        }

        $this->info("Ditemukan {$peminjamans->count()} peminjaman yang perlu di-generate surat.");

        if ($this->option('dry-run')) {
            $this->table(
                ['ID', 'User', 'Tanggal', 'Status'],
                $peminjamans->map(fn($p) => [
                    $p->id,
                    $p->user->name ?? 'Unknown',
                    $p->tanggal_peminjaman->format('Y-m-d'),
                    $p->status
                ])
            );
            return 0;
        }

        $progressBar = $this->output->createProgressBar($peminjamans->count());
        $progressBar->start();

        $successful = 0;
        $failed = 0;

        foreach ($peminjamans as $peminjaman) {
            try {
                $result = $this->letterService->generateLoanLetter($peminjaman);

                if ($result['success']) {
                    $successful++;
                    $this->newLine();
                    $this->info("✓ Generated letter for peminjaman ID: {$peminjaman->id}");
                } else {
                    $failed++;
                    $this->newLine();
                    $this->error("✗ Failed to generate letter for peminjaman ID: {$peminjaman->id}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("✗ Error generating letter for peminjaman ID {$peminjaman->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("Selesai! Berhasil: {$successful}, Gagal: {$failed}");

        return $failed > 0 ? 1 : 0;
    }

    /**
     * Regenerate existing letters
     */
    private function regenerateLetters()
    {
        $this->info('Mencari peminjaman untuk regenerate surat...');

        $query = Peminjaman::where('status', 'disetujui');

        if ($this->option('id')) {
            $query->where('id', $this->option('id'));
        }

        if (!$this->option('force')) {
            $query->whereNotNull('bukti_path');
        }

        $peminjamans = $query->get();

        if ($peminjamans->isEmpty()) {
            $this->info('Tidak ada peminjaman yang perlu di-regenerate suratnya.');
            return 0;
        }

        $this->info("Ditemukan {$peminjamans->count()} peminjaman untuk regenerate surat.");

        if ($this->option('dry-run')) {
            $this->table(
                ['ID', 'User', 'Current Path', 'Status'],
                $peminjamans->map(fn($p) => [
                    $p->id,
                    $p->user->name ?? 'Unknown',
                    $p->bukti_path ?? 'No file',
                    $p->status
                ])
            );
            return 0;
        }

        if (!$this->confirm('Apakah Anda yakin ingin regenerate semua surat ini?')) {
            $this->info('Dibatalkan.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($peminjamans->count());
        $progressBar->start();

        $successful = 0;
        $failed = 0;

        foreach ($peminjamans as $peminjaman) {
            try {
                $result = $this->letterService->forceRegenerateLetter($peminjaman);

                if ($result['success']) {
                    $successful++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error("Error regenerating letter for peminjaman ID {$peminjaman->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("Selesai! Berhasil: {$successful}, Gagal: {$failed}");

        return $failed > 0 ? 1 : 0;
    }

    /**
     * Clean up orphaned files
     */
    private function cleanupOrphanedFiles()
    {
        $this->info('Mencari file surat yang tidak terkait dengan peminjaman...');

        // Get all letter files
        $letterFiles = Storage::disk('public')->files('loan_letters');

        // Get all valid bukti_path from database
        $validPaths = Peminjaman::whereNotNull('bukti_path')
            ->where('bukti_path', '!=', '')
            ->pluck('bukti_path')
            ->toArray();

        $orphanedFiles = [];
        $totalSize = 0;

        foreach ($letterFiles as $file) {
            $relativePath = str_replace('loan_letters/', '', $file);
            $fullPath = 'loan_letters/' . $relativePath;

            if (!in_array($fullPath, $validPaths)) {
                $fileSize = Storage::disk('public')->size($file);
                $totalSize += $fileSize;

                $orphanedFiles[] = [
                    'path' => $file,
                    'size' => round($fileSize / 1024, 2) . ' KB',
                    'modified' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file))
                ];
            }
        }

        if (empty($orphanedFiles)) {
            $this->info('Tidak ada file orphaned yang ditemukan.');
            return 0;
        }

        $this->info("Ditemukan " . count($orphanedFiles) . " file orphaned (Total: " . round($totalSize / 1024, 2) . " KB)");

        if ($this->option('dry-run')) {
            $this->table(['File', 'Size', 'Modified'], $orphanedFiles);
            return 0;
        }

        if ($this->confirm('Apakah Anda yakin ingin menghapus semua file orphaned ini?')) {
            $deleted = 0;

            foreach ($orphanedFiles as $fileInfo) {
                try {
                    Storage::disk('public')->delete($fileInfo['path']);
                    $deleted++;
                } catch (\Exception $e) {
                    $this->error("Error deleting {$fileInfo['path']}: " . $e->getMessage());
                }
            }

            $this->info("Berhasil menghapus {$deleted} file orphaned.");
        } else {
            $this->info('Dibatalkan.');
        }

        return 0;
    }

    /**
     * Show loan letters statistics
     */
    private function showStatistics()
    {
        $this->info('Mengumpulkan statistik loan letters...');

        // Count by status
        $statusStats = Peminjaman::selectRaw('status, COUNT(*) as count,
                                           SUM(CASE WHEN bukti_path IS NOT NULL AND bukti_path != "" THEN 1 ELSE 0 END) as has_letter')
            ->groupBy('status')
            ->get();

        $this->info("\n=== Statistik berdasarkan Status ===");
        $this->table(
            ['Status', 'Total', 'Ada Surat', 'Tidak Ada Surat'],
            $statusStats->map(fn($s) => [
                $s->status,
                $s->count,
                $s->has_letter,
                $s->count - $s->has_letter
            ])
        );

        // File statistics
        $letterFiles = Storage::disk('public')->files('loan_letters');
        $totalFiles = count($letterFiles);
        $totalSize = 0;

        foreach ($letterFiles as $file) {
            $totalSize += Storage::disk('public')->size($file);
        }

        $this->info("\n=== Statistik File ===");
        $this->info("Total file surat: {$totalFiles}");
        $this->info("Total ukuran: " . round($totalSize / 1024 / 1024, 2) . " MB");
        $this->info("Rata-rata ukuran file: " . ($totalFiles > 0 ? round($totalSize / $totalFiles / 1024, 2) : 0) . " KB");

        // Recent activity
        $recentLetters = Peminjaman::whereNotNull('bukti_path')
            ->where('bukti_path', '!=', '')
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();

        $this->info("\n=== Aktivitas Terbaru ===");
        $this->info("Surat dibuat dalam 7 hari terakhir: {$recentLetters}");

        return 0;
    }
}
