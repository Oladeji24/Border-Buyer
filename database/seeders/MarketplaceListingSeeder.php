<?php

namespace Database\Seeders;

use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class MarketplaceListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all sellers and agents
        $sellers = User::where('role', 'seller')->get();
        $agents = User::where('role', 'agent')->get();

        // Sample marketplace listing categories
        $categories = [
            'Electronics',
            'Textiles',
            'Agricultural Products',
            'Raw Materials',
            'Manufacturing Equipment',
            'Fashion Accessories',
            'Automotive Parts',
            'Construction Materials'
        ];

        // Sample countries
        $countries = [
            'from' => ['China', 'India', 'Vietnam', 'Thailand', 'Turkey'],
            'to' => ['Nigeria', 'Kenya', 'Ghana', 'South Africa', 'Egypt']
        ];

        // Sample listing titles
        $listingTitles = [
            'Premium quality smartphones at wholesale prices',
            'Bulk textiles for fashion businesses',
            'Organic agricultural products for export',
            'Raw materials for manufacturing',
            'Industrial machinery for factories',
            'Designer fashion accessories',
            'Genuine automotive parts',
            'Construction materials for development projects'
        ];

        // Create sample marketplace listings
        for ($i = 1; $i <= 20; $i++) {
            $seller = $sellers->random();
            $agent = $agents->random();
            $category = $categories[array_rand($categories)];
            $fromCountry = $countries['from'][array_rand($countries['from'])];
            $toCountry = $countries['to'][array_rand($countries['to'])];
            $title = $listingTitles[array_rand($listingTitles)];

            MarketplaceListing::create([
                'seller_id' => $seller->id,
                'agent_id' => $agent->id,
                'title' => $title,
                'description' => "High quality {$category} available for export from {$fromCountry} to {$fromCountry}. Competitive prices and reliable supply chain. Contact for more details.",
                'category' => $category,
                'price' => rand(500, 5000),
                'country_from' => $fromCountry,
                'country_to' => $toCountry,
                'status' => ['active', 'pending', 'sold'][array_rand(['active', 'pending', 'sold'])],
                'is_featured' => (bool)rand(0, 1),
                'expires_at' => now()->addDays(rand(15, 90)),
            ]);
        }
    }
}