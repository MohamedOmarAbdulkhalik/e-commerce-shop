<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get all products and users
        $products = Product::all();
        $users = User::all();

        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No products or users found. Please run ProductsSeeder and UsersSeeder first.');
            return;
        }

        // create reviews to all products
        foreach ($products as $product) {

            $numberOfReviews = rand(3, 10);
            
            $randomUsers = $users->random(min($numberOfReviews, $users->count()));
            
            foreach ($randomUsers as $user) {
                Review::create([
                    'rating' => rand(1, 5),
                    'comment' => rand(0, 1) ? fake()->paragraph() : null,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        $this->command->info('Reviews created successfully!');
        $this->command->info('Total reviews: ' . Review::count());
    }
}