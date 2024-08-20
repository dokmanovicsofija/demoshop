<?php

/**
 * Data Population Script
 *
 * This script initializes the database connection using Eloquent ORM
 * and populates the 'categories', 'products', and 'statistics' tables with sample data.
 * It creates categories with subcategories, products distributed across these categories,
 * and adds an initial statistics record.
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Carbon;
use Infrastructure\Utility\PathHelper;

// Load environment variables
$dotenv = Dotenv::createUnsafeImmutable(PathHelper::env());
$dotenv->load();

// Initialize Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => getenv('DB_CONNECTION'),
    'host' => getenv('DB_HOST'),
    'database' => getenv('DB_DATABASE'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $dbName = getenv('DB_DATABASE');
    Capsule::statement("USE $dbName");

    // Sample categories data
    $categories = [
        [
            'code' => 'FURN',
            'title' => 'Furniture',
            'description' => 'Various types of furniture',
            'subcategories' => [
                ['code' => 'SOF', 'title' => 'Sofas', 'description' => 'Comfortable sofas and couches'],
                ['code' => 'BED', 'title' => 'Beds', 'description' => 'Beds and mattresses for a good night\'s sleep'],
            ],
        ],
        [
            'code' => 'TOYS',
            'title' => 'Toys',
            'description' => 'Toys for kids of all ages',
            'subcategories' => [
                ['code' => 'EDUT', 'title' => 'Educational Toys', 'description' => 'Toys that educate and entertain'],
                ['code' => 'ACTT', 'title' => 'Action Toys', 'description' => 'Action figures and model kits'],
            ],
        ],
    ];

    foreach ($categories as $category) {
        $categoryId = Capsule::table('categories')->insertGetId([
            'code' => $category['code'],
            'title' => $category['title'],
            'description' => $category['description'],
            'parent_id' => null,
            'created_at' => Carbon::now('Europe/Belgrade'),
            'updated_at' => Carbon::now('Europe/Belgrade'),
        ]);

        foreach ($category['subcategories'] as $subcategory) {
            Capsule::table('categories')->insert([
                'code' => $subcategory['code'],
                'title' => $subcategory['title'],
                'description' => $subcategory['description'],
                'parent_id' => $categoryId,
                'created_at' => Carbon::now('Europe/Belgrade'),
                'updated_at' => Carbon::now('Europe/Belgrade'),
            ]);
        }
    }

    // Sample products data with all fields populated
    $products = [
        ['sku' => 'SOFA001', 'title' => 'Leather Sofa', 'brand' => 'HomeComfort', 'price' => 499.99, 'category_code' => 'SOF', 'short_description' => 'Premium leather sofa', 'description' => 'A premium leather sofa with modern design.', 'image' => 'leather_sofa.jpg', 'enabled' => true, 'featured' => false, 'view_count' => 10],
        ['sku' => 'SOFA002', 'title' => 'Fabric Sofa', 'brand' => 'CozyHome', 'price' => 299.99, 'category_code' => 'SOF', 'short_description' => 'Cozy fabric sofa', 'description' => 'A cozy fabric sofa perfect for any living room.', 'image' => 'fabric_sofa.jpg', 'enabled' => true, 'featured' => true, 'view_count' => 7],
        ['sku' => 'BED001', 'title' => 'King Size Bed', 'brand' => 'DreamSleep', 'price' => 799.99, 'category_code' => 'BED', 'short_description' => 'Spacious king size bed', 'description' => 'A spacious king size bed with a comfortable mattress.', 'image' => 'king_bed.jpg', 'enabled' => true, 'featured' => false, 'view_count' => 2],
        ['sku' => 'BED002', 'title' => 'Single Bed', 'brand' => 'DreamSleep', 'price' => 399.99, 'category_code' => 'BED', 'short_description' => 'Comfortable single bed', 'description' => 'A comfortable single bed perfect for small rooms.', 'image' => 'single_bed.jpg', 'enabled' => true, 'featured' => true, 'view_count' => 3],
        ['sku' => 'TOY001', 'title' => 'Lego Set', 'brand' => 'Lego', 'price' => 49.99, 'category_code' => 'EDUT', 'short_description' => 'Educational Lego set', 'description' => 'A fun and educational Lego set for children.', 'image' => 'lego_set.jpg', 'enabled' => true, 'featured' => true, 'view_count' => 80],
        ['sku' => 'TOY002', 'title' => 'Puzzle', 'brand' => 'PuzzleWorld', 'price' => 19.99, 'category_code' => 'EDUT', 'short_description' => 'Challenging puzzle', 'description' => 'A challenging puzzle that is great for the brain.', 'image' => 'puzzle.jpg', 'enabled' => true, 'featured' => false, 'view_count' => 100],
        ['sku' => 'TOY003', 'title' => 'Action Figure', 'brand' => 'ActionHeroes', 'price' => 24.99, 'category_code' => 'ACTT', 'short_description' => 'Superhero action figure', 'description' => 'An action figure of your favorite superhero.', 'image' => 'action_figure.jpg', 'enabled' => true, 'featured' => true, 'view_count' => 30],
        ['sku' => 'TOY004', 'title' => 'Remote Control Car', 'brand' => 'SpeedRacer', 'price' => 69.99, 'category_code' => 'ACTT', 'short_description' => 'Fast RC car', 'description' => 'A fast remote control car for racing enthusiasts.', 'image' => 'rc_car.jpg', 'enabled' => true, 'featured' => false, 'view_count' => 54],
        ['sku' => 'SOFA003', 'title' => 'Corner Sofa', 'brand' => 'ComfortSpace', 'price' => 699.99, 'category_code' => 'SOF', 'short_description' => 'Spacious corner sofa', 'description' => 'A spacious corner sofa that fits perfectly in any room.', 'image' => 'corner_sofa.jpg', 'enabled' => true, 'featured' => true, 'view_count' => 5],
        ['sku' => 'BED003', 'title' => 'Bunk Bed', 'brand' => 'FunSleep', 'price' => 599.99, 'category_code' => 'BED', 'short_description' => 'Sturdy bunk bed', 'description' => 'A sturdy bunk bed that is great for kids.', 'image' => 'bunk_bed.jpg', 'enabled' => true, 'featured' => false, 'view_count' => 5],
    ];

    // Insert products
    foreach ($products as $product) {
        $categoryId = Capsule::table('categories')->where('code', $product['category_code'])->first()->id;

        Capsule::table('products')->insert([
            'sku' => $product['sku'],
            'title' => $product['title'],
            'brand' => $product['brand'],
            'price' => $product['price'],
            'category_id' => $categoryId,
            'short_description' => $product['short_description'],
            'description' => $product['description'],
            'image' => $product['image'],
            'enabled' => $product['enabled'],
            'featured' => $product['featured'],
            'view_count' => $product['view_count'],
            'created_at' => Carbon::now('Europe/Belgrade'),
            'updated_at' => Carbon::now('Europe/Belgrade'),
        ]);
    }

    // Insert initial statistics data
    Capsule::table('statistics')->insert([
        'home_view_count' => 20,  // Initial count for homepage views
        'created_at' => Carbon::now('Europe/Belgrade'),
        'updated_at' => Carbon::now('Europe/Belgrade'),
    ]);

    echo "Database populated with categories, products, and initial statistics." . PHP_EOL;

} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
