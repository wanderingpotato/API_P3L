<?php

namespace App\Console\Commands;

use App\Models\Detail_Pendapatan;
use App\Models\Penitip;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckTopSeller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-top-seller';

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
        $firstOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $topDetailPendapatan = Detail_Pendapatan::where('month', $firstOfLastMonth)
            ->orderBy('total', 'desc')
            ->first();

        if (is_null($topDetailPendapatan)) {
            $this->error('Total Not Found for last month.');
            return;
        }
        $topDetailPendapatan->bonus_pendapatan = $topDetailPendapatan->total * 0.01;
        $topDetailPendapatan->save();

        $user = Penitip::find($topDetailPendapatan->id_penitip);

        if (!$user) {
            $this->error('User Not Found for the top Detail_Pendapatan.');
            return;
        }
        $user->badge = 1;
        $user->saldo += $topDetailPendapatan->bonus_pendapatan;
        $user->save();

        $this->info('Top seller bonus and badge assigned successfully.');
        $this->info('Top seller: ' . $user->nama);
        $this->info('Bonus: ' . $topDetailPendapatan->bonus_pendapatan);
    }
}
