<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembelian;
use App\Models\Penitipan_Barang;

class CheckOverBatasWaktuAmbilPembeli extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-over-batas-waktu-ambil-pembeli';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cek over batas waktu ambil pembeli';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Pembelian::where('status', '!=', 'Hangus') 
      ->whereDate('batas_waktu_pengambilan', '<', now())
      ->update(['status' => 'Hangus']);
    }
}
