<?php

namespace App\Notifications;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceExpiryReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $service;
    public $daysLeft;

    /**
     * Create a new notification instance.
     */
    public function __construct(Service $service, int $daysLeft)
    {
        $this->service = $service;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $serviceName = $this->service->name ?? 'Hizmet';
        $customerName = $notifiable->name ?? 'Değerli Müşterimiz';

        return (new MailMessage)
            ->subject('Hizmet Yenileme Hatırlatması - ' . $serviceName)
            ->greeting('Merhaba ' . $customerName . ',')
            ->line('"' . $serviceName . '" hizmetinizin süresi ' . $this->daysLeft . ' gün sonra dolacaktır.')
            ->line('Son Kullanma Tarihi: ' . ($this->service->end_date ? $this->service->end_date->format('d.m.Y') : '-'))
            ->line('Hizmet kesintisi yaşamamak için lütfen yenileme işlemini gerçekleştiriniz.')
            ->action('Müşteri Paneline Git', url('/')) // Should be customer panel URL
            ->line('Teşekkür ederiz.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'service_id' => $this->service->id,
            'days_left' => $this->daysLeft,
            'message' => "Hizmetinizin süresi {$this->daysLeft} gün sonra doluyor.",
        ];
    }
}
