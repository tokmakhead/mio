<?php

// Ensure /tmp/storage exists for caching
if (!is_dir('/tmp/framework/views')) {
    mkdir('/tmp/framework/views', 0755, true);
    mkdir('/tmp/framework/sessions', 0755, true);
    mkdir('/tmp/framework/cache', 0755, true);
}

// Copy SQLite DB to /tmp for write access (Ephemeral! Resets on cold start)
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb) && !file_exists($targetDb)) {
    copy($sourceDb, $targetDb);
}
// If the DB is missing in /tmp (e.g. cold start), copy it again
else if (file_exists($sourceDb) && file_exists($targetDb) && filesize($targetDb) == 0) {
    copy($sourceDb, $targetDb);
}

// Forward Vercel requests to the Laravel public index.php
require __DIR__ . '/../public/index.php';
