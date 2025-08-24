<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all completed transactions and users
        $completedTransactions = Transaction::where('status', 'completed')->get();
        $users = User::all();

        // Sample review comments
        $positiveComments = [
            'Excellent service! Very professional and reliable.',
            'Great communication throughout the process.',
            'Highly recommended. Will definitely work with again.',
            'Exceeded my expectations. Very satisfied with the results.',
            'Outstanding service. Delivered on time and as promised.',
            'Very knowledgeable and helpful. Made the process smooth.',
            'Professional and efficient. Great experience overall.',
            'Top-notch service. Worth every penny.'
        ];

        $neutralComments = [
            'Good service, but there is room for improvement.',
            'Satisfied with the results, though it took longer than expected.',
            'Average experience. Nothing special but got the job done.',
            'Communication could have been better, but results were acceptable.',
            'Decent service for the price paid.'
        ];

        $negativeComments = [
            'Below average service. Expected better quality.',
            'Communication was poor and response times were slow.',
            'Not satisfied with the final results.',
            'Would not recommend based on my experience.',
            'Overpriced for the quality of service provided.'
        ];

        // Create sample reviews for completed transactions
        foreach ($completedTransactions as $transaction) {
            if (rand(0, 1)) { // 50% chance to create a review
                $reviewer = $transaction->user;

                // Determine who is being reviewed
                if ($transaction->service_request_id) {
                    $reviewee = $transaction->service_request->agent;
                    $agent = $reviewee;
                } else {
                    $reviewee = $transaction->marketplaceListing->seller;
                    $agent = $transaction->marketplaceListing->agent;
                }

                // Generate a random rating (1-5)
                $rating = rand(1, 5);

                // Select a comment based on rating
                if ($rating >= 4) {
                    $comment = $positiveComments[array_rand($positiveComments)];
                } elseif ($rating == 3) {
                    $comment = $neutralComments[array_rand($neutralComments)];
                } else {
                    $comment = $negativeComments[array_rand($negativeComments)];
                }

                Review::create([
                    'reviewer_id' => $reviewer->id,
                    'reviewee_id' => $reviewee->id,
                    'agent_id' => $agent->id,
                    'service_request_id' => $transaction->service_request_id,
                    'marketplace_listing_id' => $transaction->marketplace_listing_id,
                    'transaction_id' => $transaction->id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'is_public' => (bool)rand(0, 1),
                ]);
            }
        }
    }
}