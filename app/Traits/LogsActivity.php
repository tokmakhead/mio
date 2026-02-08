<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    public function logActivity(string $action, ?array $metadata = null)
    {
        $modelName = strtolower(class_basename($this));

        ActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => "{$modelName}.{$action}",
            'subject_type' => get_class($this),
            'subject_id' => $this->id,
            'metadata' => $metadata ?? $this->getLogMetadata($action),
        ]);
    }

    protected function getLogMetadata(string $action): array
    {
        // Default metadata: only significant fields for the model
        // Can be overridden in models
        return [
            'name' => $this->name ?? $this->number ?? $this->id,
        ];
    }
}
