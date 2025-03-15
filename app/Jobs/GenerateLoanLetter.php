<?php

namespace App\Jobs;

use App\Models\Peminjaman;
use App\Services\LoanLetterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLoanLetter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $peminjamanId;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjamanId = $peminjaman->id;
    }

    public function handle(LoanLetterService $loanLetterService)
    {
        $peminjaman = Peminjaman::find($this->peminjamanId);

        if ($peminjaman) {
            $loanLetterService->generateLoanLetter($peminjaman);
        }
    }
}
