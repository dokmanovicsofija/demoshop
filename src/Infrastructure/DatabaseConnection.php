<?php

namespace Infrastructure;

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Infrastructure\Utility\PathHelper;

/**
 * Class DatabaseConnection
 *
 * This class handles the initialization of the database connection using the Eloquent ORM.
 * It loads environment variables, sets up the database connection parameters, and bootstraps Eloquent.
 */
class DatabaseConnection
{
    /**
     * Initializes the database connection.
     *
     * This method loads environment variables from the .env file, sets up the database connection
     * using configuration parameters, and initializes the Eloquent ORM.
     *
     * @return void
     */
    public static function init(): void
    {
        // Load environment variables from the .env file
        $dotenv = Dotenv::createUnsafeImmutable(PathHelper::env());
        $dotenv->load();

        // Set up the database connection using Eloquent ORM
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
        // Make the database connection globally available
        $capsule->setAsGlobal();
        // Boot Eloquent ORM to use the connection
        $capsule->bootEloquent();
    }
}