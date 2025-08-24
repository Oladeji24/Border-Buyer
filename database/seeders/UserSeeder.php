<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@borderbuyers.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'country' => 'United States',
            'phone' => '+1234567890',
        ]);

        // Create Nigerian agents
        $tunde = User::create([
            'name' => 'Tunde Adekunle',
            'email' => 'tunde@borderbuyers.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'country' => 'Nigeria',
            'phone' => '+2348012345678',
            'profile_image' => 'https://images.unsplash.com/photo-1557862921-37829c790f19?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80',
        ]);

        $tunde->agentProfile()->create([
            'bio' => 'Experienced border trade agent with 10+ years of facilitating international transactions between Nigeria and China.',
            'specialization' => 'Electronics, Textiles',
            'experience_years' => 10,
            'languages' => json_encode(['English', 'Yoruba', 'Mandarin']),
            'verification_status' => 'verified',
            'rating' => 4.8,
            'completed_transactions' => 245,
        ]);

        $ngozi = User::create([
            'name' => 'Ngozi Okafor',
            'email' => 'ngozi@borderbuyers.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'country' => 'Nigeria',
            'phone' => '+2348098765432',
            'profile_image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80',
        ]);

        $ngozi->agentProfile()->create([
            'bio' => 'Specialized in agricultural products and raw materials trade between Nigeria and China for 7 years.',
            'specialization' => 'Agriculture, Raw Materials',
            'experience_years' => 7,
            'languages' => json_encode(['English', 'Igbo', 'Mandarin']),
            'verification_status' => 'verified',
            'rating' => 4.6,
            'completed_transactions' => 178,
        ]);

        // Create Chinese agents
        $wei = User::create([
            'name' => 'Wei Zhang',
            'email' => 'wei@borderbuyers.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'country' => 'China',
            'phone' => '+8612345678901',
            'profile_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80',
        ]);

        $wei->agentProfile()->create([
            'bio' => 'Manufacturing and electronics specialist with 8 years of experience in China-Africa trade relations.',
            'specialization' => 'Manufacturing, Electronics',
            'experience_years' => 8,
            'languages' => json_encode(['Mandarin', 'English', 'Cantonese']),
            'verification_status' => 'verified',
            'rating' => 4.7,
            'completed_transactions' => 312,
        ]);

        $li = User::create([
            'name' => 'Li Mei',
            'email' => 'li@borderbuyers.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'country' => 'China',
            'phone' => '+8619876543210',
            'profile_image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&h=256&q=80',
        ]);

        $li->agentProfile()->create([
            'bio' => 'Textile and fashion industry expert with 5 years of experience facilitating trade between China and African markets.',
            'specialization' => 'Textiles, Fashion',
            'experience_years' => 5,
            'languages' => json_encode(['Mandarin', 'English']),
            'verification_status' => 'verified',
            'rating' => 4.5,
            'completed_transactions' => 156,
        ]);

        // Create sample buyers
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Buyer ' . $i,
                'email' => 'buyer' . $i . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'buyer',
                'country' => $i % 2 == 0 ? 'Nigeria' : 'Kenya',
                'phone' => '+234' . rand(10000000, 99999999),
            ]);
        }

        // Create sample sellers
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Seller ' . $i,
                'email' => 'seller' . $i . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'seller',
                'country' => $i % 2 == 0 ? 'China' : 'India',
                'phone' => '+86' . rand(100000000, 999999999),
            ]);
        }
    }
}