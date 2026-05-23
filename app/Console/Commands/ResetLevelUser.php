<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\ResetLevel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetLevelUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'level:reset-level-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset level user otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            $reset = ResetLevel::lockForUpdate()->first();

            if (!$reset) {
                return;
            }

            if ($reset->status !== 'aktif') {
                return;
            }

            $sekarang = Carbon::now();

            if (!$reset->last_reset_at) {

                $reset->update([
                    'last_reset_at' => $sekarang
                ]);

                return;
            }

            $resetSelanjutnya = Carbon::parse(
                $reset->last_reset_at
            )->addDays($reset->lama_hari);

            $jamReset = Carbon::createFromFormat(
                'H:i:s',
                $reset->jam_reset
            );

            $resetSelanjutnya->setTime(
                $jamReset->hour,
                $jamReset->minute,
                $jamReset->second
            );

            if ($sekarang->lt($resetSelanjutnya)) {
                return;
            }

            $levelAwal = Level::orderBy('min_poin')->first();

            if (!$levelAwal) {
                return;
            }

            User::query()->update([
                'level_id' => $levelAwal->id,
                'poin_level' => 0,
            ]);

            $reset->update([
                'last_reset_at' => $resetSelanjutnya
            ]);
    
            $this->info('Reset Level Berhasil');
        });
    }
}
