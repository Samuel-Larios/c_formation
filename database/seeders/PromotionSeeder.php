<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $sites = DB::table('sites')->pluck('id');

        foreach ($sites as $siteId) {
            // Créer 3 promotions par site
            for ($i = 1; $i <= 3; $i++) {
                $promotionId = DB::table('promotions')->insertGetId([
                    'num_promotion' => 'Promo-' . $siteId . '-' . $i,
                    'site_id' => $siteId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Associer des étudiants à cette promotion
                $students = DB::table('students')
                    ->where('site_id', $siteId)
                    ->inRandomOrder()
                    ->limit(7)
                    ->pluck('id');

                foreach ($students as $studentId) {
                    DB::table('promotion_apprenant')->insert([
                        'promotion_id' => $promotionId,
                        'student_id' => $studentId,
                        'site_id' => $siteId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
