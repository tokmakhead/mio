<?php
/**
 * MIONEX Storage Diagnosis & Fix Tool
 * This script checks if the storage link is correctly configured and if uploaded files exist.
 */

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$app->make(Illuminate\Contracts\Http\Kernel::class)->handle(Illuminate\Http\Request::capture());

header('Content-Type: text/plain');

echo "MIONEX Storage Diagnosis\n";
echo "========================\n\n";

// 1. Check Directories
$publicStoragePath = storage_path('app/public');
$brandingPath = $publicStoragePath . '/branding';
$symlinkPath = public_path('storage');

echo "1. Directory Check:\n";
echo "Public Storage Path: " . $publicStoragePath . " (" . (is_dir($publicStoragePath) ? "EXISTS" : "MISSING") . ")\n";
echo "Branding Path: " . $brandingPath . " (" . (is_dir($brandingPath) ? "EXISTS" : "MISSING") . ")\n";
echo "Symlink Path: " . $symlinkPath . " (" . (file_exists($symlinkPath) ? (is_link($symlinkPath) ? "LİNK" : "DIRECTORY/FILE") : "MISSING") . ")\n\n";

// 2. Test File Check
echo "2. Recent Files in branding/:\n";
if (is_dir($brandingPath)) {
    $files = glob($brandingPath . '/*');
    if (empty($files)) {
        echo "No files found in branding directory.\n";
    } else {
        foreach ($files as $file) {
            echo "- " . basename($file) . " (" . filesize($file) . " bytes)\n";
        }
    }
} else {
    echo "Branding directory does not exist.\n";
}
echo "\n";

// 3. Attempt Fix (Create Symlink)
echo "3. Attempting to Fix Symlink:\n";
if (file_exists($symlinkPath)) {
    if (is_link($symlinkPath)) {
        echo "Symlink already exists. Skipping.\n";
    } else {
        echo "Public/storage exists but is NOT a link. This is a problem! It should usually be deleted manually if it's an empty folder.\n";
    }
} else {
    echo "Creating symlink...\n";
    try {
        Artisan::call('storage:link');
        echo "Artisan storage:link command executed.\n";
        echo Artisan::output();
    } catch (\Exception $e) {
        echo "Error creating symlink via Artisan: " . $e->getMessage() . "\n";

        // Manual attempt
        echo "Attempting manual symlink...\n";
        if (symlink($publicStoragePath, $symlinkPath)) {
            echo "Manual symlink created successfully.\n";
        } else {
            echo "FAILED to create manual symlink. Please check folder permissions.\n";
        }
    }
}

echo "\n========================\n";
echo "Diagnosis finished. Please check the results above.\n";
echo "If the symlink was created, try reloading your logo.\n";
echo "SECURITY: DELETE THIS FILE AFTER USE!\n";
