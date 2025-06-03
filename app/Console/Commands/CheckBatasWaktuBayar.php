<?php

namespace App\Console\Commands;

use App\Models\Pembeli;
use App\Models\Pembelian;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckBatasWaktuBayar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-batas-waktu-bayar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        // \Log::info('Current time: ' . $now);

        $expiredRecords = Pembelian::where('batas_waktu', '<=', $now)
            ->where('status', 'Proses')->get();


        // \Log::info('Expired Records Count: ' . $expiredRecords->count());

        foreach ($expiredRecords as $record) {
            $user = Pembeli::find($record->id_pembeli);
            $user->increment('poin', $record->point_digunakan);
            $record->status = 'Batal';
            $record->save();
        }

        // $this->info('Expired records updated.');
    }
}
