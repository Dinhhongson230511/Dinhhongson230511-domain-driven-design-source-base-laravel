<?php

namespace App\Console\Commands;

use Database\Seeders\AdditionalNotificationSeeder;
use Illuminate\Console\Command;

class AdditionalNotificationSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'additional-notification-seeder {--config=:}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Additional Notification Seeder Command';

    /**
     * @var AdditionalNotificationSeeder
     */
    private AdditionalNotificationSeeder $additionalNotificationSeeder;


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->additionalNotificationSeeder = new AdditionalNotificationSeeder();
        $this->additionalNotificationSeeder->callWith(AdditionalNotificationSeeder::class, [$this->option('config')]);
        return 0;
    }
}
