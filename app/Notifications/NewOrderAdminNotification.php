<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderAdminNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $order)
    {
        //
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
    public function toMail($notifiable)
    {
        $this->order->loadMissing([
            'lineItems.productVariant.attributeValues.attribute',
            'lineItems.productVariant.product.translation',
            'currency',
            'billingAddress',
            'shippingAddress',
            'couponUsages',
        ]);

        $customerName = $this->order->billingAddress->name ?? 'Customer';

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Xtremez Store Order #'.$this->order->reference_number.' placed by '.$customerName)
            ->view('emails.admin-order-notification', [
                'order' => $this->order,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
