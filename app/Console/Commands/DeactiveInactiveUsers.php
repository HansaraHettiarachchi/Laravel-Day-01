<?php

namespace App\Console\Commands;

use App\Models\NewUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactiveInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactive-inactive-users';

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

        $threshold = Carbon::now()->subWeeks(2);

        $inactiveUsers = NewUser::where('loged_time', '<', $threshold)
            ->where('status', 'Active')
            ->update(['status' => 'Deactive']);

        Log::channel("myLog")->info("Deactivated {$inactiveUsers} inactive users.");

        $this->info("Deactivated {$inactiveUsers} inactive users.");
    }
}
