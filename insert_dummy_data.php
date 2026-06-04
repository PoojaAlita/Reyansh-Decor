<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Slider;
use App\Models\HomeBanner;
use App\Models\HomeVideo;
use App\Models\Cart;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\DB;

$artifactsDir = 'C:\Users\brpan\.gemini\antigravity\brain\3f7e7475-0557-4b4c-9fe8-79817645e40a';
$storageDir = storage_path('app/public/dummy');

if (!File::exists($storageDir)) {
    File::makeDirectory($storageDir, 0755, true);
} // Create the directory if it does not exist

// Explicit file mappings for generated images
$files = [
    'curtain_slider_1_1772018155620.png' => 'slider1.png',
    'curtain_slider_2_1772018250520.png' => 'slider2.png',
    'curtain_product_1_1772018533234.png' => 'product1.png',
    'curtain_product_2_1772018625477.png' => 'product2.png',
    'curtain_product_3_1772018656827.png' => 'product3.png',
];

foreach ($files as $source => $dest) {
    if (File::exists("$artifactsDir\\$source")) {
        File::copy("$artifactsDir\\$source", "$storageDir\\$dest");
    } else {
        echo "Source file missing: $source\n";
    }
}

// 1. Create or Get Admin
$admin_id = 1;
$admin = DB::table('admin')->first();
if (!$admin) {
    $admin_id = DB::table('admin')->insertGetId([
        'username' => 'admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('password'),
        'isshown' => 1
    ]);
} else {
    $admin_id = $admin->id;
}

// Clear existing items to avoid duplicates on multiple runs
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Cart::truncate();
Slider::truncate();
Product::truncate();
Subcategory::truncate();
Category::truncate();
HomeBanner::truncate();
HomeVideo::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// 2. Categories
$cat1 = Category::create(['name' => 'Premium Curtains', 'admin_id' => $admin_id]);
$cat2 = Category::create(['name' => 'Sheers & Tulle', 'admin_id' => $admin_id]);

// 3. Subcategories
$sub1 = Subcategory::create(['category_id' => $cat1->id, 'subcat_name' => 'Blackout', 'admin_id' => $admin_id]);
$sub2 = Subcategory::create(['category_id' => $cat2->id, 'subcat_name' => 'Living Room', 'admin_id' => $admin_id]);

// 4. Products
Product::create([
    'category_id' => $cat1->id,
    'subcategory_id' => $sub1->id,
    'admin_id' => $admin_id,
    'name' => 'Luxurious Beige Linen Drape',
    'sku' => 'RD-BNG-001',
    'description' => 'A high-quality beige linen textured curtain fabric elegantly draped, with soft lighting highlighting the fabrics premium texture.',
    'price' => 3500,
    'sale_price' => 2999,
    'stock' => 50,
    'main_image' => 'dummy/product1.png',
]);

Product::create([
    'category_id' => $cat1->id,
    'subcategory_id' => $sub1->id,
    'admin_id' => $admin_id,
    'name' => 'Charcoal Velvet Blackout',
    'sku' => 'RD-CHV-002',
    'description' => 'Premium dark charcoal velvet blackout curtain, neatly gathered to show heavy, luxurious folds. Perfect for bedrooms.',
    'price' => 4500,
    'stock' => 30,
    'main_image' => 'dummy/product2.png',
]);

Product::create([
    'category_id' => $cat2->id,
    'subcategory_id' => $sub2->id,
    'admin_id' => $admin_id,
    'name' => 'Breezy White Sheer Window Pane',
    'sku' => 'RD-BWS-003',
    'description' => 'Semi-sheer white curtain waving gently, light passing through to show the soft, breezy material. Clean, minimalist look.',
    'price' => 1800,
    'sale_price' => 1500,
    'stock' => 100,
    'main_image' => 'dummy/product3.png',
]);

// 5. Sliders
Slider::create([
    'title' => 'Exquisite Living Decor',
    'sub_title' => 'Premium fabrics designed to transform your living spaces.',
    'image' => 'dummy/slider1.png',
    'admin_id' => $admin_id,
]);

Slider::create([
    'title' => 'Sophisticated Elegance',
    'sub_title' => 'Experience absolute comfort with our premium blackout collection.',
    'image' => 'dummy/slider2.png',
    'admin_id' => $admin_id,
]);

// 6. Home Banners
HomeBanner::create([
    'title' => 'Exclusive Winter Collection',
    'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&q=80&w=800',
    'link' => '#',
    'position' => 1,
    'isshown' => 1,
    'admin_id' => $admin_id,
]);

HomeBanner::create([
    'title' => 'Luxury Bedding Essentials',
    'image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&q=80&w=800',
    'link' => '#',
    'position' => 2,
    'isshown' => 1,
    'admin_id' => $admin_id,
]);

// 7. Home Videos
HomeVideo::create([
    'title' => 'Craftsmanship Behind Reyansh Decor',
    'video_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw', // Curtain Showcase Video
    'position' => 1,
    'ishown' => 1,
    'admin_id' => $admin_id,
]);

echo "Dummy data successfully generated. \n";
