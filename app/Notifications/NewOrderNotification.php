<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order = null)
    {
        $this->order = $order ?? (object)[
            'id' => rand(1000, 9999),
            'total' => rand(50, 500) + (rand(0, 99) / 100),
            'status' => 'pending'
        ];
    }

    public function via($notifiable): array
    {
        // تأكد من وجود 'database' في المصفوفة
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Order #' . $this->order->id)
                    ->line('A new order has been placed.')
                    ->line('Order ID: ' . $this->order->id)
                    ->line('Total Amount: $' . number_format($this->order->total, 2))
                    ->action('View Orders', url('/admin/orders'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'New order #' . $this->order->id . ' has been placed',
            'order_id' => $this->order->id,
            'total' => $this->order->total,
            'action_url' => '/admin/orders',
            'type' => 'order_created'
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'total' => $this->order->total,
            'status' => $this->order->status,
        ];
    }
}