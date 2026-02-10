<?php

// Ensure /tmp/storage exists for caching
if (!is_dir('/tmp/framework/views')) {
    mkdir('/tmp/framework/views', 0755, true);
    mkdir('/tmp/framework/sessions', 0755, true);
    mkdir('/tmp/framework/cache', 0755, true);
}

// SQLite copy logic removed for PostgreSQL support

// Forward Vercel requests to the Laravel public index.php
require __DIR__ . '/../public/index.php';
