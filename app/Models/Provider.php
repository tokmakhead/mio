<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'types',
        'website',
        'email',
        'phone',
        'notes',
    ];

    protected $casts = [
        'types' => 'array',
    ];

    /**
     * Get formatted types labels
     */
    public function getTypesLabelsAttribute(): string
    {
        $labels = [
            'hosting' => 'Hosting',
            'domain' => 'Domain',
            'ssl' => 'SSL',
            'email' => 'E-posta',
            'other' => 'Diğer',
        ];

        return collect($this->types)
            ->map(fn($type) => $labels[$type] ?? $type)
            ->join(', ');
    }

    /**
     * Get badge color for a type
     */
    public static function getTypeBadgeColor(string $type): string
    {
        return match ($type) {
            'hosting' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-white',
            'domain' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-white',
            'ssl' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-white',
            'email' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-white',
            'other' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-white',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    /**
     * Get type label
     */
    public static function getTypeLabel(string $type): string
    {
        return match ($type) {
            'hosting' => 'Hosting',
            'domain' => 'Domain',
            'ssl' => 'SSL',
            'email' => 'E-posta',
            'other' => 'Diğer',
            default => $type,
        };
    }

    /**
     * Relations
     */
    public function services()
    {
        return $this->hasMany(\App\Models\Service::class); // Placeholder
    }
}
