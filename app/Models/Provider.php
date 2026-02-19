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
        'custom_type',
        'tax_office',
        'tax_number',
        'address',
        'website',
        'email',
        'phone',
        'notes',
    ];

    protected $casts = [
        'types' => 'array',
    ];

    /**
     * Available Provider Types Configuration
     */
    public const AVAILABLE_TYPES = [
        'hosting' => ['label' => 'Hosting', 'color' => 'blue', 'icon' => 'server'],
        'domain' => ['label' => 'Domain', 'color' => 'green', 'icon' => 'globe'],
        'ssl' => ['label' => 'SSL', 'color' => 'yellow', 'icon' => 'lock-closed'],
        'email' => ['label' => 'E-posta', 'color' => 'purple', 'icon' => 'mail'],
        'server' => ['label' => 'Sunucu', 'color' => 'indigo', 'icon' => 'chip'],
        'license' => ['label' => 'Lisans', 'color' => 'pink', 'icon' => 'key'],
        'other' => ['label' => 'Diğer', 'color' => 'gray', 'icon' => 'dots-horizontal'],
    ];

    /**
     * Get validated types list
     */
    public static function getAvailableTypes(): array
    {
        return self::AVAILABLE_TYPES;
    }

    /**
     * Get formatted types labels
     */
    public function getTypesLabelsAttribute(): string
    {
        return collect($this->types)
            ->map(function ($type) {
                if ($type === 'other' && !empty($this->custom_type)) {
                    return $this->custom_type . ' (Diğer)';
                }
                return self::AVAILABLE_TYPES[$type]['label'] ?? $type;
            })
            ->join(', ');
    }

    /**
     * Get badge color for a type
     */
    public static function getTypeBadgeColor(string $type): string
    {
        $color = self::AVAILABLE_TYPES[$type]['color'] ?? 'gray';

        return match ($color) {
            'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'green' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
            'pink' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    /**
     * Get type label
     */
    public static function getTypeLabel(string $type): string
    {
        return self::AVAILABLE_TYPES[$type]['label'] ?? $type;
    }

    /**
     * Relations
     */
    public function services()
    {
        return $this->hasMany(\App\Models\Service::class);
    }
}
