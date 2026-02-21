<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterActivityLog;
use App\Models\MasterLicenseInstance;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MasterCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'master:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Master Panel sistem temizliği (Log rotasyonu ve atıl instance temizliği)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Master Panel Temizlik İşlemi Başlatıldı...');

        // 1. Log Rotasyonu (30 günden eski logları dosyaya arşivle ve sil)
        $oldLogs = MasterActivityLog::where('created_at', '<', now()->subDays(30))->get();
        if ($oldLogs->count() > 0) {
            $logContent = "--- ARCHIVED LOGS: " . now()->toDateTimeString() . " ---\n";
            foreach ($oldLogs as $log) {
                $logContent .= "[{$log->created_at}] [{$log->action}] [User: {$log->user_id}] - {$log->description}\n";
            }

            $archiveName = 'logs/archived_activity_' . now()->format('Y_m_d_His') . '.log';
            Storage::disk('local')->put($archiveName, $logContent);

            MasterActivityLog::where('created_at', '<', now()->subDays(30))->delete();
            $this->info($oldLogs->count() . ' adet log arşivlendi ve silindi.');
        }

        // 2. Atıl Instance Temizliği (90 günden fazla süredir senkronize olmayanları sil)
        $staleInstances = MasterLicenseInstance::where('last_sync_at', '<', now()->subDays(90))->delete();
        if ($staleInstances > 0) {
            $this->info($staleInstances . ' adet atıl lisans kurulumu (instance) silindi.');
        }

        $this->info('Sistem temizliği başarıyla tamamlandı.');
    }
}
