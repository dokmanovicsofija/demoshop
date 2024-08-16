<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Migration\CreateAdmins;
use Migration\CreateProducts;
use Migration\CreateCategories;
use Migration\CreateStatistics;
use Dotenv\Dotenv;

/**
 * Load environment variables from the .env file.
 */
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Initialize Eloquent ORM and set up the database connection.
 */
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => getenv('DB_CONNECTION'),
    'host' => getenv('DB_HOST'),
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

    /**
     * Check if the database exists, and create it if it does not.
     */
    $databaseExists = Capsule::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);

    if (empty($databaseExists)) {
        Capsule::statement("CREATE DATABASE IF NOT EXISTS $dbName");
        echo "Database '$dbName' has been created." . PHP_EOL;
    } else {
        echo "Database '$dbName' already exists." . PHP_EOL;
    }

    // Set the database to use
    Capsule::statement("USE $dbName");

    /**
     * Run the migrations to create tables.
     */
    $migrations = [
        CreateAdmins::class,
        CreateCategories::class,
        CreateProducts::class,
        CreateStatistics::class,
    ];

    foreach ($migrations as $migrationClass) {
        $migration = new $migrationClass();
        $migration->up();
        echo "Migration for " . get_class($migration) . " was successful." . PHP_EOL;
    }

    /**
     * Display the list of tables in the database.
     */
    $tables = Capsule::select('SHOW TABLES');
    echo "Tables present in the database '" . getenv('DB_DATABASE') . "':" . PHP_EOL;
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo $tableName . PHP_EOL;
    }

    /**
     * Prompt the user to enter admin username and password.
     */
    echo "Enter the admin username: ";
    $username = trim(fgets(STDIN));

    do {
        echo "Enter the admin password: ";
        $password = trim(fgets(STDIN));

        $isValidPassword = validatePassword($password);

        if (!$isValidPassword) {
            echo "Password must contain at least 8 characters, 1 upper letter, 1 lower letter, a number, and a special character." . PHP_EOL;
        }
    } while (!$isValidPassword);

    // Check if the admin already exists
    $adminExists = Capsule::table('admins')->where('username', $username)->exists();

    if ($adminExists) {
        echo "Admin with username '$username' already exists." . PHP_EOL;
    } else {
        // Insert the new admin into the database
        Capsule::table('admins')->insert([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'token' => bin2hex(random_bytes(16)),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "Admin '$username' has been successfully created." . PHP_EOL;
    }

} catch (\Exception $e) {
    echo 'An error occurred: ' . $e->getMessage() . PHP_EOL;
}

/**
 * Validate the password according to the specified criteria.
 *
 * @param string $password
 * @return bool
 */
function validatePassword(string $password): bool
{
    return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $password);
}