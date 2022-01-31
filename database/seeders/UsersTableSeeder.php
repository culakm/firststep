<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_count = max((int) $this->command->ask('How many users to generate?', 20),1);

        User::factory()->defaultUser()->create();
        User::factory()->culakUser()->create();
        User::factory()->count($users_count - 1)->create(); // Pretoze 1 sme uz vygenerovali ako defaultUser
    }
}
