<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier; // Import the Supplier model

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample data for suppliers
        $suppliers = [
            ['name' => 'Supplier Alpha', 'contact_info' => 'alpha@example.com'],
            ['name' => 'Supplier Beta', 'contact_info' => 'beta@example.com'],
            ['name' => 'Supplier Gamma', 'contact_info' => 'gamma@example.com'],
        ];

        // Insert the data into the suppliers table
        foreach ($suppliers as $supplier) {
            Supplier::create($supplier); // This references the imported Supplier model
        }
    }
}
