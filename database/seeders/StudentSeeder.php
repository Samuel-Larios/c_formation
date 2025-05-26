<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $sites = DB::table('sites')->pluck('id');

        foreach ($sites as $siteId) {
            $nbStudents = rand(10, 20);
            for ($i = 0; $i < $nbStudents; $i++) {
                DB::table('students')->insert([
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'sexe' => $faker->randomElement(['M', 'F']),
                    'situation_matrimoniale' => $faker->randomElement(['Marié(e)', 'Célibataire', 'Divorcé(e)', 'Veuf(veuve)', null]),
                    'situation_handicape' => $faker->randomElement(['Handicapé moteur', 'Handicape sensoriel (auditif et visuel)', 'Handicape mental', null]),
                    'date_naissance' => $faker->date(),
                    'contact' => $faker->numerify('+229 ########'),
                    'contact_pers1' => $faker->numerify('+229 ########'),
                    'contact_pers2' => $faker->numerify('+229 ########'),
                    'contact_pers3' => $faker->numerify('+229 ########'),
                    'contact_pers4' => $faker->numerify('+229 ########'),
                    'contact_pers5' => $faker->numerify('+229 ########'),
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'), // Mot de passe hashé
                    'state_of_origin' => $faker->state,
                    'state_of_residence' => $faker->state,
                    'state' => $faker->state,
                    'lga' => $faker->city,
                    'community' => $faker->word,
                    'site_id' => $siteId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
