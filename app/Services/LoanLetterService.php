<?php

namespace App\Services;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class LoanLetterService
{
    private $maxExecutionTime = 45; // seconds
    private $maxMemoryLimit = '512M';
    private $cacheTimeout = 3600; // 1 hour

    /**
     * Generate loan letter dengan optimasi maksimal untuk performa
     */
    public function generateLoanLetter(Peminjaman $peminjaman)
    {
        $startTime = microtime(true);

        try {
            // Set optimized PHP settings
            $this->optimizePhpSettings();

            // Check if letter already exists and is valid
            if ($this->isValidExistingLetter($peminjaman)) {
                Log::info("Using existing valid letter for peminjaman ID: {$peminjaman->id}");
                return [
                    'success' => true,
                    'path' => $peminjaman->bukti_path,
                    'message' => 'Surat peminjaman sudah tersedia'
                ];
            }

            // Get template path with caching
            $templatePath = $this->getTemplatePath($peminjaman->jangka);

            // Validate template
            if (!$this->validateTemplate($templatePath)) {
                throw new Exception("Template tidak valid atau tidak ditemukan");
            }

            // Load and cache user data
            $user = $this->getCachedUser($peminjaman->id_users);
            if (!$user) {
                throw new Exception("User dengan ID {$peminjaman->id_users} tidak ditemukan");
            }

            // Load template processor with memory optimization
            $templateProcessor = $this->createOptimizedTemplateProcessor($templatePath);

            // Set basic template variables
            $this->setBasicTemplateVariables($templateProcessor, $user, $peminjaman);

            // Load and process detail peminjaman
            $items = $this->getProcessedItems($peminjaman);
            if (empty($items)) {
                throw new Exception("Tidak ada detail peminjaman untuk peminjaman ID: {$peminjaman->id}");
            }

            // Clone rows with batch processing for large datasets
            $this->processItemsInBatches($templateProcessor, $items);

            // Save with optimized settings
            $result = $this->saveProcessedTemplate($templateProcessor, $peminjaman);

            $executionTime = round(microtime(true) - $startTime, 2);
            Log::info("Surat peminjaman berhasil di-generate", [
                'peminjaman_id' => $peminjaman->id,
                'file_path' => $result['path'],
                'file_size' => $result['file_size'] ?? 'unknown',
                'execution_time' => $executionTime . 's'
            ]);

            return $result;
        } catch (Exception $e) {
            $executionTime = round(microtime(true) - $startTime, 2);
            Log::error('Gagal membuat surat peminjaman: ' . $e->getMessage(), [
                'peminjaman_id' => $peminjaman->id,
                'execution_time' => $executionTime . 's',
                'memory_usage' => $this->getMemoryUsage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Fallback dengan template yang sudah dioptimasi
            return $this->createOptimizedFallback($peminjaman, $templatePath ?? null);
        } finally {
            // Cleanup memory
            $this->cleanupMemory();
        }
    }

    /**
     * Optimize PHP settings for document generation
     */
    private function optimizePhpSettings()
    {
        // Set memory limit
        ini_set('memory_limit', $this->maxMemoryLimit);

        // Set time limit
        set_time_limit($this->maxExecutionTime);

        // Optimize for file operations
        ini_set('auto_detect_line_endings', true);

        // Disable output buffering to save memory
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Force garbage collection
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }

    /**
     * Check if existing letter is valid
     */
    private function isValidExistingLetter(Peminjaman $peminjaman)
    {
        if (!$peminjaman->bukti_path) {
            return false;
        }

        $filePath = storage_path('app/public/' . $peminjaman->bukti_path);

        // Check if file exists and is not empty
        if (!file_exists($filePath) || filesize($filePath) < 1000) {
            return false;
        }

        // Check if file was created recently (less than 1 hour ago) to avoid regenerating
        $fileTime = filemtime($filePath);
        $timeDiff = time() - $fileTime;

        return $timeDiff < 3600; // 1 hour
    }

    /**
     * Get template path with caching
     */
    private function getTemplatePath($jangka)
    {
        $cacheKey = "template_path_{$jangka}";

        return Cache::remember($cacheKey, $this->cacheTimeout, function () use ($jangka) {
            return $jangka === 'pendek'
                ? public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PENDEK.docx')
                : public_path('templates/SURAT_PEMINJAMAN_ALAT_DIGIKOM_JANGKA_PANJANG.docx');
        });
    }

    /**
     * Validate template file
     */
    private function validateTemplate($templatePath)
    {
        if (!file_exists($templatePath)) {
            return false;
        }

        $templateSize = filesize($templatePath);
        if ($templateSize > 20 * 1024 * 1024) { // 20MB limit
            Log::warning("Template file terlalu besar: " . round($templateSize / 1024 / 1024, 2) . "MB");
            return false;
        }

        return true;
    }

    /**
     * Get cached user data
     */
    private function getCachedUser($userId)
    {
        $cacheKey = "user_data_{$userId}";

        return Cache::remember($cacheKey, 300, function () use ($userId) { // 5 minutes cache
            return User::find($userId);
        });
    }

    /**
     * Create optimized template processor
     */
    private function createOptimizedTemplateProcessor($templatePath)
    {
        // Create temporary copy to avoid locking original template
        $tempPath = storage_path('app/temp/template_' . uniqid() . '.docx');
        Storage::makeDirectory('temp');

        if (!copy($templatePath, $tempPath)) {
            throw new Exception("Gagal membuat copy template");
        }

        try {
            $processor = new TemplateProcessor($tempPath);

            // Set memory optimization settings if available
            if (method_exists($processor, 'setTempDir')) {
                $processor->setTempDir(storage_path('app/temp'));
            }

            return $processor;
        } finally {
            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }

    /**
     * Set basic template variables
     */
    private function setBasicTemplateVariables($templateProcessor, $user, $peminjaman)
    {
        \Carbon\Carbon::setLocale('id');

        $templateProcessor->setValue('tanggal_surat', \Carbon\Carbon::now()->translatedFormat('d F Y'));
        $templateProcessor->setValue('nama_peminjam', $user->name ?? '-');
        $templateProcessor->setValue('nim_peminjam', $user->nim ?? '-');
        $templateProcessor->setValue('no_hp_peminjam', $user->no_telp ?? '-');
        $templateProcessor->setValue('alasan_peminjaman', $peminjaman->alasan ?? '-');
    }

    /**
     * Get processed items with optimized query
     */
    private function getProcessedItems($peminjaman)
    {
        $cacheKey = "peminjaman_items_{$peminjaman->id}";

        return Cache::remember($cacheKey, 300, function () use ($peminjaman) {
            $detailPeminjaman = DetailPeminjaman::where('id_peminjaman', $peminjaman->id)
                ->with('inventaris:id,nama') // Only select needed fields
                ->get();

            $items = [];
            foreach ($detailPeminjaman as $index => $detail) {
                if (!$detail->inventaris) {
                    continue;
                }

                $items[] = [
                    'nomor_barang' => $index + 1,
                    'nama_barang' => $detail->inventaris->nama,
                    'jumlah_barang' => $detail->kuantitas,
                    'tanggal_peminjaman' => \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y'),
                    'tanggal_selesai' => \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('d/m/Y'),
                ];
            }

            return $items;
        });
    }

    /**
     * Process items in batches for large datasets
     */
    private function processItemsInBatches($templateProcessor, $items)
    {
        $batchSize = 50; // Process 50 items at a time

        if (count($items) <= $batchSize) {
            // Small dataset, process all at once
            $templateProcessor->cloneRowAndSetValues('nomor_barang', $items);
        } else {
            // Large dataset, process in batches
            $batches = array_chunk($items, $batchSize);

            foreach ($batches as $batchIndex => $batch) {
                if ($batchIndex === 0) {
                    $templateProcessor->cloneRowAndSetValues('nomor_barang', $batch);
                } else {
                    // For subsequent batches, append to existing table
                    foreach ($batch as $item) {
                        $templateProcessor->cloneRow('nomor_barang', 1);
                        foreach ($item as $key => $value) {
                            $templateProcessor->setValue($key, $value);
                        }
                    }
                }

                // Force garbage collection between batches
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            }
        }
    }

    /**
     * Save processed template with optimization
     */
    private function saveProcessedTemplate($templateProcessor, $peminjaman)
    {
        Storage::makeDirectory('public/loan_letters');

        $fileName = 'surat_peminjaman_PD-' . $peminjaman->id . '.docx';
        $outputPath = storage_path('app/public/loan_letters/' . $fileName);

        // Save with compression if available
        $templateProcessor->saveAs($outputPath);

        // Verify file creation
        if (!file_exists($outputPath)) {
            throw new Exception("Gagal menyimpan file surat di: {$outputPath}");
        }

        $outputSize = filesize($outputPath);
        if ($outputSize < 1000) {
            throw new Exception("File output terlalu kecil, kemungkinan ada error dalam generate");
        }

        // Update peminjaman record
        $peminjaman->bukti_path = 'loan_letters/' . $fileName;
        $peminjaman->save();

        return [
            'success' => true,
            'path' => $peminjaman->bukti_path,
            'file_size' => round($outputSize / 1024, 2) . 'KB',
            'message' => 'Surat peminjaman berhasil di-generate'
        ];
    }

    /**
     * Create optimized fallback
     */
    private function createOptimizedFallback($peminjaman, $templatePath = null)
    {
        try {
            if (!$templatePath) {
                $templatePath = $this->getTemplatePath($peminjaman->jangka);
            }

            if (!file_exists($templatePath)) {
                throw new Exception("Template tidak ditemukan untuk fallback");
            }

            Storage::makeDirectory('public/loan_letters');

            $fileName = 'surat_peminjaman_PD-' . $peminjaman->id . '_TEMPLATE.docx';
            $fallbackPath = storage_path('app/public/loan_letters/' . $fileName);

            if (!copy($templatePath, $fallbackPath)) {
                throw new Exception("Gagal membuat fallback");
            }

            $peminjaman->bukti_path = 'loan_letters/' . $fileName;
            $peminjaman->save();

            Log::warning("Template fallback dibuat untuk peminjaman ID: {$peminjaman->id}");

            return [
                'success' => true,
                'path' => $peminjaman->bukti_path,
                'message' => 'Template kosong disimpan. Admin perlu mengisi manual.'
            ];
        } catch (Exception $e) {
            Log::error('Gagal membuat fallback: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Gagal membuat surat peminjaman dan fallback',
                'message' => 'Silakan hubungi admin untuk generate manual'
            ];
        }
    }

    /**
     * Get current memory usage
     */
    private function getMemoryUsage()
    {
        return round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB';
    }

    /**
     * Cleanup memory
     */
    private function cleanupMemory()
    {
        // Clear template processor from memory
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }

        // Clear any temp files
        $tempDir = storage_path('app/temp');
        if (is_dir($tempDir)) {
            $files = glob($tempDir . '/template_*.docx');
            foreach ($files as $file) {
                if (file_exists($file) && time() - filemtime($file) > 300) { // 5 minutes old
                    unlink($file);
                }
            }
        }
    }

    /**
     * Force regenerate letter (delete existing and create new)
     */
    public function forceRegenerateLetter(Peminjaman $peminjaman)
    {
        // Delete existing letter
        if ($peminjaman->bukti_path) {
            Storage::disk('public')->delete($peminjaman->bukti_path);
            $peminjaman->bukti_path = null;
            $peminjaman->save();
        }

        // Clear cache
        Cache::forget("peminjaman_items_{$peminjaman->id}");
        Cache::forget("user_data_{$peminjaman->id_users}");

        // Generate new letter
        return $this->generateLoanLetter($peminjaman);
    }

    /**
     * Check if letter generation is in progress (to prevent duplicate processing)
     */
    public function isGenerationInProgress($peminjamanId)
    {
        return Cache::has("generating_letter_{$peminjamanId}");
    }

    /**
     * Set generation lock
     */
    public function setGenerationLock($peminjamanId)
    {
        Cache::put("generating_letter_{$peminjamanId}", true, 300); // 5 minutes
    }

    /**
     * Release generation lock
     */
    public function releaseGenerationLock($peminjamanId)
    {
        Cache::forget("generating_letter_{$peminjamanId}");
    }
}
