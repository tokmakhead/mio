<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$maintenanceFile = __DIR__ . '/../storage/framework/maintenance.php';

echo "<h1>MIONEX Maintenance Unlocker</h1>";

if (file_exists($maintenanceFile)) {
    echo "<p>Maintenance file found: $maintenanceFile</p>";
    if (unlink($maintenanceFile)) {
        echo "<p style='color:green; font-weight:bold;'>✅ SUCCESS: Maintenance mode has been DISABLED.</p>";
        echo "<p><a href='/'>Go to Dashboard</a></p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>❌ ERROR: Could not delete the maintenance file. Please check permissions.</p>";
    }
} else {
    echo "<p style='color:blue;'>ℹ️ Maintenance mode is already INACTIVE (file not found).</p>";
    echo "<p><a href='/'>Go to Dashboard</a></p>";
}
