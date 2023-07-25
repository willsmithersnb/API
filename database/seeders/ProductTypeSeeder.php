<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->where('name', 'Standalone')->update(['name' => 'Supplements and Reagents']);
        $productTypesNames = ['Specialized Media', 'Specialized Base Media', 'Specialized Buffers', 'Specialized Balanced Salts'];
        foreach ($productTypesNames as $productTypeName) {
            ProductType::create([
                'name' => $productTypeName,
                'customizable' => false,
                'liquid_enabled' => false,
                'powder_enabled' => false,
                'cgmp_enabled' => false,
                'vial_enabled' => false,
            ]);
        }
    }
}
