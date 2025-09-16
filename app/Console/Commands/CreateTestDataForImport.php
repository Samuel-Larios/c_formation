<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use App\Models\Promotion;

class CreateTestDataForImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testdata:create-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer les données de test nécessaires pour l\'import des étudiants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification des données de test...');

        // Vérifier les sites existants
        $this->info('Sites existants :');
        $sites = Site::all();
        foreach ($sites as $site) {
            $this->info("- {$site->designation} (ID: {$site->id})");
        }

        // Créer les sites de test s'ils n'existent pas
        $testSites = ['Site Principal', 'Site Secondaire'];
        foreach ($testSites as $siteName) {
            $existingSite = Site::where('designation', $siteName)->first();
            if (!$existingSite) {
                $site = Site::create([
                    'designation' => $siteName,
                    'description' => 'Site de test pour import étudiants',
                    'location' => 'Test Location',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->info("✓ Site créé : {$siteName} (ID: {$site->id})");
            } else {
                $this->info("✓ Site existe déjà : {$siteName} (ID: {$existingSite->id})");
            }
        }

        $this->info("\nPromotions existantes :");
        $promotions = Promotion::all();
        foreach ($promotions as $promotion) {
            $this->info("- {$promotion->num_promotion} (ID: {$promotion->id}, Site: {$promotion->site->designation})");
        }

        // Créer les promotions de test
        $testPromotions = [
            ['num_promotion' => 'PROM-2024-001', 'site_name' => 'Site Principal'],
            ['num_promotion' => 'PROM-2024-002', 'site_name' => 'Site Secondaire']
        ];

        foreach ($testPromotions as $promoData) {
            $site = Site::where('designation', $promoData['site_name'])->first();
            if ($site) {
                $existingPromo = Promotion::where('num_promotion', $promoData['num_promotion'])->first();
                if (!$existingPromo) {
                    $promotion = Promotion::create([
                        'num_promotion' => $promoData['num_promotion'],
                        'site_id' => $site->id,
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-12-31',
                        'description' => 'Promotion de test',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $this->info("✓ Promotion créée : {$promoData['num_promotion']} (ID: {$promotion->id})");
                } else {
                    $this->info("✓ Promotion existe déjà : {$promoData['num_promotion']} (ID: {$existingPromo->id})");
                }
            }
        }

        $this->info("\nDonnées de test prêtes pour l'import !");
        $this->info("Vous pouvez maintenant :");
        $this->info("1. Ouvrir test_students_import.csv avec Excel");
        $this->info("2. Le sauvegarder en format .xlsx");
        $this->info("3. L'importer via la page Export/Import des étudiants");

        return 0;
    }
}
