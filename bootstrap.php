<?php
/**
 * Application Bootstrap
 * Loads session, configuration, autoloader, and database connection.
 */

session_start();

define('ROOT_PATH', __DIR__);
define('PUBLIC_PATH', __DIR__ . '/public');
define('APP_PATH', __DIR__ . '/app');

// Load environment variables from .env file
if (file_exists(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Composer autoloader
if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    require_once ROOT_PATH . '/vendor/autoload.php';
} else {
    // Fallback manual autoloader
    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $baseDir = ROOT_PATH . '/app/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    });
    // Load helpers manually
    require_once ROOT_PATH . '/app/Helpers/helpers.php';
}

// Load configuration constants
require_once ROOT_PATH . '/app/Config/constants.php';

// Initialize Config class
App\Config\Config::load();

// Initialize database connection
$pdo = App\Core\Database::connect();

// Make $pdo available globally (for backward compatibility)
$GLOBALS['pdo'] = $pdo;

// Initialize Service Container
App\Core\ServiceProvider::init();
