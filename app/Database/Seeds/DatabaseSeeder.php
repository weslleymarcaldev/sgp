<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Chama outros seeders
        $this->call(ProjectSeeder::class);
    }
}
