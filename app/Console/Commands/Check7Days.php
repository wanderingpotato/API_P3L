<?php

namespace App\Console\Commands;

use App\Models\Penitipan_Barang;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Check7Days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check7-days';

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
        $today = Carbon::now()->toDateString();
        $expiredBarang = Penitipan_Barang::whereDate('batas_ambil', '<=', $today)->get();

        if ($expiredBarang->isEmpty()) {
            $this->info('No PenitipanBarang to update.');
            return;
        }

        foreach ($expiredBarang as $barang) {
            $barang->status = 'Untuk Donasi';
            $barang->save();
        }

        $this->info('PenitipanBarang statuses updated to Untuk Donasi.');
        $this->info('Updated records: ' . $expiredBarang->count());
    }
}
