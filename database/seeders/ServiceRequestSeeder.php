<?php

namespace Database\Seeders;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all buyers and agents
        $buyers = User::where('role', 'buyer')->get();
        $agents = User::where('role', 'agent')->get();

        // Sample service request categories
        $categories = [
            'Electronics',
            'Textiles',
            'Agricultural Products',
            'Raw Materials',
            'Manufacturing',
            'Fashion',
            'Automotive Parts',
            'Construction Materials'
        ];

        // Sample countries
        $countries = [
            'from' => ['Nigeria', 'Kenya', 'Ghana', 'South Africa', 'Egypt'],
            'to' => ['China', 'India', 'Vietnam', 'Thailand', 'Turkey']
        ];

        // Create sample service requests
        for ($i = 1; $i <= 15; $i++) {
            $buyer = $buyers->random();
            $agent = $agents->random();
            $category = $categories[array_rand($categories)];
            $fromCountry = $countries['from'][array_rand($countries['from'])];
            $toCountry = $countries['to'][array_rand($countries['to'])];

            ServiceRequest::create([
                'buyer_id' => $buyer->id,
                'agent_id' => $agent->id,
                'title' => "Need help sourcing {$category} from {$toCountry}",
                'description' => "Looking for a reliable agent to help me source quality {$category} from {$toCountry} to {$fromCountry}. I need someone who can verify product quality and handle all logistics.",
                'category' => $category,
                'budget' => rand(1000, 10000),
                'country_from' => $fromCountry,
                'country_to' => $toCountry,
                'status' => ['open', 'assigned', 'in_progress', 'completed'][array_rand(['open', 'assigned', 'in_progress', 'completed'])],
                'deadline' => now()->addDays(rand(7, 30)),
            ]);
        }
    }
}