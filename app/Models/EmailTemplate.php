<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'type',
        'subject',
        'html_body',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Replace {{variable}} placeholders with actual values.
     */
    public function render(array $variables): string
    {
        $body = $this->html_body;
        foreach ($variables as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }
        return $body;
    }

    public function renderSubject(array $variables): string
    {
        $subject = $this->subject;
        foreach ($variables as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }
        return $subject;
    }
}
