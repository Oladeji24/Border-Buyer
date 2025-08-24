<?php

namespace Database\Seeders;

use App\Models\ServiceRequest;
use App\Models\MarketplaceListing;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all users, service requests, and marketplace listings
        $users = User::all();
        $serviceRequests = ServiceRequest::all();
        $marketplaceListings = MarketplaceListing::all();

        // Sample payment methods
        $paymentMethods = [
            'Bank Transfer',
            'Credit Card',
            'PayPal',
            'Escrow',
            'Cryptocurrency'
        ];

        // Sample currencies
        $currencies = ['USD', 'EUR', 'GBP', 'NGN', 'CNY'];

        // Create sample transactions for service requests
        foreach ($serviceRequests as $request) {
            if (rand(0, 1)) { // 50% chance to create a transaction
                $user = $request->buyer;
                $status = ['pending', 'completed', 'failed'][array_rand(['pending', 'completed', 'failed'])];
                $completedAt = $status === 'completed' ? now()->subDays(rand(1, 30)) : null;

                Transaction::create([
                    'user_id' => $user->id,
                    'service_request_id' => $request->id,
                    'amount' => $request->budget * 0.2, // 20% of budget as agent fee
                    'currency' => $currencies[array_rand($currencies)],
                    'status' => $status,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_reference' => 'SR-' . uniqid(),
                    'completed_at' => $completedAt,
                ]);
            }
        }

        // Create sample transactions for marketplace listings
        foreach ($marketplaceListings as $listing) {
            if (rand(0, 1)) { // 50% chance to create a transaction
                $user = $users->where('role', 'buyer')->random();
                $status = ['pending', 'completed', 'failed'][array_rand(['pending', 'completed', 'failed'])];
                $completedAt = $status === 'completed' ? now()->subDays(rand(1, 30)) : null;

                Transaction::create([
                    'user_id' => $user->id,
                    'marketplace_listing_id' => $listing->id,
                    'amount' => $listing->price,
                    'currency' => $currencies[array_rand($currencies)],
                    'status' => $status,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_reference' => 'ML-' . uniqid(),
                    'completed_at' => $completedAt,
                ]);
            }
        }
    }
}