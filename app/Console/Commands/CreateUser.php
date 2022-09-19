<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user {count=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user using factory';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = $this->argument('count');
        try {
            $newUsers = User::factory($count)->create();
            $this->info('Users was successfully created!');
            $this->table(
                ['Name', 'Email'],
                $newUsers->map->only('name', 'email')
            );
        } catch (\Throwable $th) {
            $this->error('Something went wrong!');
        }
    }
}
