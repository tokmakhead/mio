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
        echo "Symlink already exists and is a valid link. Skipping.\n";
    } else {
        echo "Public/storage exists but is a DIRECTORY/FILE, not a link. Attempting to remove it...\n";

        // Try to remove it if it's a directory
        if (is_dir($symlinkPath)) {
            // Check if empty
            $items = scandir($symlinkPath);
            $force = isset($_GET['force']) && $_GET['force'] == '1';

            if (count($items) <= 2) { // Just . and ..
                if (rmdir($symlinkPath)) {
                    echo "Empty directory removed successfully.\n";
                } else {
                    echo "FAILED to remove directory. Please delete 'public/storage' folder manually via FTP/File Manager.\n";
                }
            } elseif ($force) {
                echo "Force mode active. Attempting recursive deletion of 'public/storage'...\n";
                function deleteDirectory($dir)
                {
                    if (!file_exists($dir))
                        return true;
                    if (!is_dir($dir))
                        return unlink($dir);
                    foreach (scandir($dir) as $item) {
                        if ($item == '.' || $item == '..')
                            continue;
                        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
                            return false;
                    }
                    return rmdir($dir);
                }
                if (deleteDirectory($symlinkPath)) {
                    echo "Directory removed recursively.\n";
                } else {
                    echo "FAILED to remove directory recursively. Please delete it manually.\n";
                }
            } else {
                echo "Directory is NOT empty. Please check its content and delete/move it manually:\n";
                foreach ($items as $item) {
                    if ($item != '.' && $item != '..') {
                        echo "- $item\n";
                    }
                }
                echo "\nTo force delete this folder via this script, add ?force=1 to the URL.\n";
                echo "WARNING: This will permanently delete everything inside 'public/storage'!\n";
            }
        } else {
            // It's a file?
            if (unlink($symlinkPath)) {
                echo "File removed successfully.\n";
            }
        }
    }
}

// Re-check if it was cleared
if (!file_exists($symlinkPath)) {
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
            echo "FAILED to create manual symlink. Please check folder permissions or ask your host if symlink() is enabled.\n";
        }
    }
}

echo "\n========================\n";
echo "Diagnosis finished. Please check the results above.\n";
echo "If the symlink was created, try reloading your logo.\n";
echo "SECURITY: DELETE THIS FILE AFTER USE!\n";
