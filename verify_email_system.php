<?php

use Illuminate\Support\Facades\Mail;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSent;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Mock Mailer to avoid actual sending but trigger events?
// Or just try to send and catch exception if no config, but event should fire if we mock it?

// Let's just create an EmailLog entry manually to test model
$log = EmailLog::create([
    'to' => 'test@example.com',
    'subject' => 'Test Log',
    'body' => 'Body',
    'status' => 'sent',
    'sent_at' => now(),
]);

echo "Created Log ID: " . $log->id . "\n";

// Test Queue Setting on EmailSetting
$setting = \App\Models\EmailSetting::firstOrNew(['id' => 1]);
$setting->use_queue = true;
$setting->save();

echo "Email Setting use_queue: " . ($setting->use_queue ? 'true' : 'false') . "\n";

// Revert
$setting->use_queue = false;
$setting->save();
echo "Reverted use_queue.\n";
