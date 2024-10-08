<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [];
        for ($i = 0; $i < 100; $i++) {
            $items[] = [
                'itemName' => 'Item ' . ($i + 1),
                'itemCategory' => 'Category ' . chr(65 + ($i % 3)), // Rotates between A, B, C
                'description' => 'Description of Item ' . ($i + 1),
                'unitPrice' => mt_rand(50, 500), // Random unit price between 50 and 500
                'status' => $i % 2 == 0 ? 'active' : 'inactive', // Alternates between active and inactive
                'sellingPrice' => mt_rand(50, 500), // Random selling price between 50 and 500
                // 'barcode' => Str::random(12), // Generates a 12-character random string
                'vatApplicable' => $i % 2 == 0, // Alternates between true and false
            ];
        }

        // Insert items into the database
        DB::table('items')->insert($items);
    }
}
