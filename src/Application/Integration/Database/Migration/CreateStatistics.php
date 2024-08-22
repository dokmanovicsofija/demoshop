<?php

namespace Application\Integration\Database\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateStatistics
 *
 * This migration creates the `statistics` table in the database.
 * The `statistics` table is used to store various statistics related to the application, such as the number of views on the homepage.
 */
class CreateStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * This method is responsible for creating the `statistics` table.
     * The table includes an auto-incrementing primary key (`id`), a field to track the number of views on the homepage (`home_view_count`),
     * and timestamp fields for `created_at` and `updated_at`.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method drops the `statistics` table if it exists, effectively reversing the migration.
     *
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->dropIfExists('statistics');
    }
}
