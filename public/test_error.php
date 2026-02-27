<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test database connection
try {
    $db = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    echo "Database connection successful!\n";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}

// Test file permissions
$storage_path = __DIR__ . '/../storage';
$bootstrap_path = __DIR__ . '/../bootstrap/cache';
$paths_to_check = [
    $storage_path,
    $storage_path . '/app',
    $storage_path . '/framework',
    $storage_path . '/logs',
    $bootstrap_path
];

echo "\nChecking directory permissions:\n";
foreach ($paths_to_check as $path) {
    if (file_exists($path)) {
        echo $path . " exists. ";
        echo "Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
        echo "Writable: " . (is_writable($path) ? "Yes" : "No") . "\n";
    } else {
        echo $path . " does not exist!\n";
    }
}

// Test PHP extensions
$required_extensions = [
    'BCMath',
    'Ctype',
    'Fileinfo',
    'JSON',
    'Mbstring',
    'OpenSSL',
    'PDO',
    'PDO_MySQL',
    'Tokenizer',
    'XML',
    'cURL',
    'GD'
];

echo "\nChecking PHP extensions:\n";
foreach ($required_extensions as $extension) {
    echo $extension . ": " . (extension_loaded(strtolower($extension)) ? "Installed" : "Missing!") . "\n";
}

// Check PHP version
echo "\nPHP Version: " . phpversion() . "\n";

// Check Laravel requirements
echo "\nLaravel Requirements:\n";
echo "PHP >= 8.0: " . (version_compare(PHP_VERSION, '8.0.0') >= 0 ? "Pass" : "Fail") . "\n";

// Check storage symlink
$public_storage = __DIR__ . '/storage';
echo "\nStorage symlink status: " . (is_link($public_storage) ? "Created" : "Missing") . "\n";

// Check environment file
$env_file = __DIR__ . '/../.env';
echo "\n.env file status: " . (file_exists($env_file) ? "Exists" : "Missing") . "\n";

// Display important PHP settings
echo "\nPHP Settings:\n";
$settings = [
    'max_execution_time',
    'memory_limit',
    'upload_max_filesize',
    'post_max_size'
];

foreach ($settings as $setting) {
    echo $setting . ": " . ini_get($setting) . "\n";
}

// Check Laravel storage directory permissions
function checkDirectoryPermissions($dir) {
    if (!file_exists($dir)) {
        echo "$dir directory does not exist!\n";
        return;
    }

    echo "\nChecking permissions for $dir:\n";
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $perms = substr(sprintf('%o', $item->getPerms()), -4);
        echo $item->getPathname() . " - Permissions: " . $perms . "\n";
    }
}

checkDirectoryPermissions($storage_path);
