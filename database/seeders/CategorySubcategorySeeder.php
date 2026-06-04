<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoriesData = [
            'CURTAIN' => [
                'Printed Curtain',
                'Velvet Embossed Curtain',
                'Sheer Curtain',
                'Designer Curtain',
                'Digital Curtain',
                'Solid curtain',
                'Punched curtain',
            ],
            'FLAT BEDSHEET' => [
                'Flat Bedsheet',
                'Elastic/Fitted Bedsheet',
            ],
            'FOOT MAT' => [],
            'TABLE MAT' => [],
            'APPLIANCE COVER' => [
                'Appliance Cover',
                'LED Cover',
                'AC Cover',
            ],
        ];

        foreach ($categoriesData as $categoryName => $subcategories) {
            $category = Category::firstOrCreate([
                'name' => $categoryName
            ], [
                'isshown' => '1',
                'admin_id' => 1
            ]);

            foreach ($subcategories as $subcatName) {
                SubCategory::firstOrCreate([
                    'category_id' => $category->id,
                    'subcat_name' => $subcatName
                ], [
                    'isshown' => '1',
                    'admin_id' => 1
                ]);
            }
        }
    }
}
