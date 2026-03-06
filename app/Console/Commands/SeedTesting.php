<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\TestingSeeder;

class SeedTestData extends Command
{
    protected $signature = 'seed:testing';
    protected $description = 'Run the TestingSeeder to create test tenants';

    public function handle(): void
    {
        $this->call('db:seed', ['--class' => 'TestingSeeder']);
        $this->info('Testing seeder completed!');
    }
}
