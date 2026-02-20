<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_user_id',
        'action',
        'subject_type',
        'subject_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function getActionLabelAttribute()
    {
        $map = [
            'user.login' => 'Giriş Yaptı',
            'user.logout' => 'Çıkış Yaptı',
            'customer.created' => 'Yeni Müşteri Oluşturuldu',
            'customer.updated' => 'Müşteri Güncellendi',
            'customer.deleted' => 'Müşteri Silindi',
            'invoice.created' => 'Yeni Fatura Oluşturuldu',
            'invoice.updated' => 'Fatura Güncellendi',
            'invoice.deleted' => 'Fatura Silindi',
            'payment.created' => 'Yeni Ödeme Kaydedildi',
            'payment.updated' => 'Ödeme Güncellendi',
            'payment.deleted' => 'Ödeme Silindi',
            'service.created' => 'Yeni Hizmet Tanımlandı',
            'service.updated' => 'Hizmet Güncellendi',
            'service.deleted' => 'Hizmet İptal Edildi',
            'quote.created' => 'Yeni Teklif Hazırlandı',
            'quote.updated' => 'Teklif Güncellendi',
            'quote.deleted' => 'Teklif Silindi',
            'provider.created' => 'Yeni Tedarikçi Eklendi',
            'provider.updated' => 'Tedarikçi Güncellendi',
            'provider.deleted' => 'Tedarikçi Silindi',
        ];

        return $map[$this->action] ?? $this->action;
    }
}
