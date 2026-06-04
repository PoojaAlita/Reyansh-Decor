<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class SpecificProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminId = 1;

        // 1. Find the Digital Curtain subcategory
        $subCat = SubCategory::where('subcat_name', 'Digital Curtain')->first();
        if (!$subCat) {
            // Fallback: create category and subcategory if they don't exist
            $category = Category::firstOrCreate(['name' => 'CURTAIN'], ['isshown' => '1', 'admin_id' => $adminId]);
            $subCat = SubCategory::firstOrCreate([
                'category_id' => $category->id,
                'subcat_name' => 'Digital Curtain'
            ], [
                'isshown' => '1',
                'admin_id' => $adminId
            ]);
        }

        // 2. Create the Specific Product
        $productName = "8 FT Polyester Digital Printed Room Darkening Eyelet Door Curtain, Grey Pack of 2";
        // Unsplash image representing the grey digital printed curtain
        $mainImageUrl = "https://images.unsplash.com/photo-1544450558-d39b8bc42a26?auto=format&fit=crop&q=80&w=1200";

        $product = Product::firstOrCreate([
            'sku' => 'RD-DIG-GRY-8FT'
        ], [
            'category_id' => $subCat->category_id,
            'subcategory_id' => $subCat->id,
            'admin_id' => $adminId,
            'name' => $productName,
            'description' => "Transform your space with these premium 8 FT (242 cm) Polyester Digital Printed Door Curtains. Designed for room darkening and privacy, these eyelet curtains feature a sophisticated grey and black digital print that complements any modern decor. Pack of 2 curtains. Material: High-quality room-darkening polyester.",
            'price' => 2299, // MRP (from screenshot cross-out)
            'sale_price' => 699, // Sale price (from screenshot)
            'stock' => 50,
            'isshown' => '1',
            'main_image' => $mainImageUrl // Using URL for demo, usually it would be relative path in storage
        ]);

        // 3. Add Variants (Sizes) as per screenshot
        $variantsData = [
            ['size' => 'Window 5 Feet', 'price' => 699, 'material' => 'Polyester'],
            ['size' => 'Window 6 Feet', 'price' => 599, 'material' => 'Polyester'],
            ['size' => 'Door 7 Feet', 'price' => 699, 'material' => 'Polyester'],
            ['size' => 'Door 8 Feet', 'price' => 699, 'material' => 'Polyester'],
        ];

        foreach ($variantsData as $variant) {
            ProductVariant::updateOrCreate([
                'product_id' => $product->id,
                'size' => $variant['size']
            ], [
                'color' => 'Grey',
                'material' => $variant['material'],
                'price' => $variant['price'],
                'stock' => 10,
                'isshown' => '1',
                'admin_id' => $adminId
            ]);
        }

        // 4. Add Additional Gallery Images
        $galleryImages = [
            "https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=600",
            "https://images.unsplash.com/photo-1615529328331-f8917597711f?auto=format&fit=crop&q=80&w=600"
        ];

        foreach ($galleryImages as $imgUrl) {
            ProductImage::firstOrCreate([
                'product_id' => $product->id,
                'image' => $imgUrl
            ], [
                'isshown' => '1',
                'admin_id' => $adminId
            ]);
        }
    }
}
