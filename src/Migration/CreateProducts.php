<?php

namespace Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateProducts
 *
 * This migration creates the `products` table in the database.
 * The `products` table is used to store information about the products in the catalog, including their categories, prices, and other details.
 */
class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * This method is responsible for creating the `products` table.
     * The table includes an auto-incrementing primary key (`id`), a foreign key to the `categories` table (`category_id`),
     * a unique `sku` for identifying products, a `title`, an optional `brand`, a `price`, an optional `short_description` and `description`,
     * an optional `image` path, and flags for whether the product is `enabled` or `featured`.
     * It also tracks the number of views (`view_count`) and includes timestamp fields for `created_at` and `updated_at`.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('sku')->unique();
            $table->string('title');
            $table->string('brand')->nullable();
            $table->decimal('price', 8, 2);
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method drops the `products` table if it exists, effectively reversing the migration.
     *
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->dropIfExists('products');
    }
}
