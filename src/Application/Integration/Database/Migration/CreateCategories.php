<?php

namespace Application\Integration\Database\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCategories
 *
 * This migration creates the `categories` table in the database.
 * The `categories` table is used to organize products into hierarchical groups.
 */
class CreateCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * This method is responsible for creating the `categories` table.
     * The table includes an auto-incrementing primary key (`id`), an optional `parent_id` for nested categories,
     * a unique `code`, a `title`, an optional `description`, and timestamp fields for `created_at` and `updated_at`.
     * The `parent_id` field references the `id` field in the same table, allowing for hierarchical categories.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method drops the `categories` table if it exists, effectively reversing the migration.
     *
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->dropIfExists('categories');
    }
}
