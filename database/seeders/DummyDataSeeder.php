<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Slider;
use App\Models\HomeBanner;
use App\Models\HomeVideo;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminId = 1;

        // 1. Create Sliders
        $sliders = [
            ['title' => 'Spring Collection 2026', 'sub_title' => 'Refresh Your Home', 'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=1920', 'link' => '#', 'isshown' => '1', 'admin_id' => $adminId],
            ['title' => 'Premium Curtains', 'sub_title' => 'Exclusive Designs', 'image' => 'https://images.unsplash.com/photo-1544450558-d39b8bc42a26?auto=format&fit=crop&q=80&w=1920', 'link' => '#', 'isshown' => '1', 'admin_id' => $adminId],
            ['title' => 'Bedroom Essentials', 'sub_title' => 'Comfort Flat Bedsheets', 'image' => 'https://images.unsplash.com/photo-1622397333309-305609439972?auto=format&fit=crop&q=80&w=1920', 'link' => '#', 'isshown' => '1', 'admin_id' => $adminId]
        ];

        foreach ($sliders as $sliderData) {
            Slider::updateOrCreate(['title' => $sliderData['title']], $sliderData);
        }

        // 2. Create Home Banners
        $banners = [
            ['title' => 'Special Offer', 'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=1200', 'link' => '#', 'position' => 1, 'isshown' => '1', 'admin_id' => $adminId],
            ['title' => 'New Arrivals', 'image' => 'https://images.unsplash.com/photo-1544450558-d39b8bc42a26?auto=format&fit=crop&q=80&w=1200', 'link' => '#', 'position' => 2, 'isshown' => '1', 'admin_id' => $adminId]
        ];

        foreach ($banners as $bannerData) {
            HomeBanner::updateOrCreate(['title' => $bannerData['title']], $bannerData);
        }

        // 3. Create Home Videos
        $videos = [
            ['title' => 'How to Style Your Curtains', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'thumbnail' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=800', 'position' => 1, 'ishown' => '1', 'admin_id' => $adminId]
        ];

        foreach ($videos as $videoData) {
            HomeVideo::updateOrCreate(['title' => $videoData['title']], $videoData);
        }

        // 4. Create Products & Product Images
        $subcategories = SubCategory::all();
        
        $unsplashProductPool = [
            "https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&q=80&w=600",
            "https://images.unsplash.com/photo-1544450558-d39b8bc42a26?auto=format&fit=crop&q=80&w=600",
            "https://images.unsplash.com/photo-1615529328331-f8917597711f?auto=format&fit=crop&q=80&w=600",
            "https://images.unsplash.com/photo-1560185127-6a4305810e03?auto=format&fit=crop&q=80&w=600",
            "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&q=80&w=600",
            "https://images.unsplash.com/photo-1622397333309-305609439972?auto=format&fit=crop&q=80&w=600",
        ];

        foreach ($subcategories as $subcat) {
            $categoryId = $subcat->category_id;
            $childCategory = ChildCategory::where('subcategory_id', $subcat->id)->first();
            $childCategoryId = $childCategory ? $childCategory->id : null;

            for ($i = 1; $i <= 3; $i++) {
                $productName = $subcat->subcat_name . ' Premium Selection ' . $i;
                $mainImg = $unsplashProductPool[array_rand($unsplashProductPool)];
                
                $product = Product::updateOrCreate([
                    'sku' => strtoupper(Str::slug($productName)) . '-' . rand(1000, 9999)
                ], [
                    'category_id' => $categoryId,
                    'subcategory_id' => $subcat->id,
                    'child_category_id' => $childCategoryId,
                    'admin_id' => $adminId,
                    'name' => $productName,
                    'description' => 'Experience elegance with ' . $productName . '. Expertly crafted for your premium home decor needs.',
                    'price' => rand(1200, 3500),
                    'sale_price' => rand(800, 2500),
                    'stock' => rand(20, 200),
                    'isshown' => '1',
                    'main_image' => $mainImg
                ]);

                if ($product->wasRecentlyCreated) {
                    for ($j = 1; $j <= 2; $j++) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $unsplashProductPool[array_rand($unsplashProductPool)],
                            'isshown' => '1',
                            'admin_id' => $adminId
                        ]);
                    }
                }
            }
        }
    }
}
