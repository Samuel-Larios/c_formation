<?php

namespace Database\Seeders;

use App\Models\User;
use Dflydev\DotAccessData\Util;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiteSeeder::class,
            StudentSeeder::class,
            UtilisateurSeeder::class,
        ]);
    }

}
