<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderSuccess extends Notification
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.order-receipt', [
            'order' => $this->order,
        ]);

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Your Order #'.$this->order->reference_number.' has been placed')
            ->view('emails.order-success', [
                'order' => $this->order,
            ])
            ->attachData($pdf->output(), "Receipt-{$this->order->reference_number}.pdf", [
                'mime' => 'application/pdf',
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
