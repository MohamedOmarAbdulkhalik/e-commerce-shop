<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Order #' . $this->order->id)
                    ->line('A new order has been placed.')
                    ->line('Order ID: ' . $this->order->id)
                    ->line('Total Amount: $' . $this->order->total)
                    ->action('View Order', url('/admin/orders/' . $this->order->id))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'message' => 'New order #' . $this->order->id . ' has been placed',
            'order_id' => $this->order->id,
            'total' => $this->order->total,
            'action_url' => '/admin/orders/' . $this->order->id,
        ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'total' => $this->order->total,
        ];
    }
}