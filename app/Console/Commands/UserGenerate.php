<?php
namespace App\Console\Commands;

use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\User;

class UserGenerate extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:user_generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $numberOfUsers = 40;

        User::factory()->random()
            ->count($numberOfUsers)
            ->create();

        $this->info("Generated $numberOfUsers users successfully.");

        return 0;
    }
}
