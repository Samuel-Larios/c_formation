<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sites = [
            ['designation' => 'Site de Cotonou', 'emplacement' => 'Cotonou'],
            ['designation' => 'Site de Porto-Novo', 'emplacement' => 'Porto-Novo'],
            ['designation' => 'Site de Parakou', 'emplacement' => 'Parakou'],
            ['designation' => 'Site de Natitingou', 'emplacement' => 'Natitingou'],
        ];

        DB::table('sites')->insert($sites);
    }
}
