<?php

namespace Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAdmins
 *
 * This migration creates the `admins` table in the database.
 * The `admins` table is used to store information about administrative users, including their usernames, passwords, and authentication tokens.
 */
class CreateAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * This method is responsible for creating the `admins` table.
     * The table includes an auto-incrementing primary key (`id`), a unique `username`, a `password`, an optional `token`, and timestamp fields for `created_at` and `updated_at`.
     *
     * @return void
     */
    public function up(): void
    {
        Capsule::schema()->create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method drops the `admins` table if it exists, effectively reversing the migration.
     *
     * @return void
     */
    public function down(): void
    {
        Capsule::schema()->dropIfExists('admins');
    }
}
