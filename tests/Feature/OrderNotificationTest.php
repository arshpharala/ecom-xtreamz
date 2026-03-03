<?php

namespace Tests\Feature;

use App\Models\Cart\Order;
use App\Models\User;
use App\Notifications\OrderSuccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderNotificationTest extends TestCase
{
    // use RefreshDatabase; 

    public function test_order_success_notification_has_pdf_attachment()
    {
        Notification::fake();

        // Mock Order
        $order = \Mockery::mock(Order::class)->makePartial();
        $order->reference_number = 'ORD-12345';
        $order->email = 'test@example.com';
        
        // Mock relationships
        $order->shouldReceive('loadMissing')->andReturnSelf();
        $order->lineItems = collect([]);
        $order->currency = (object)['code' => 'AED'];
        $order->billingAddress = new class
        {
            public string $name = 'Test User';

            public function render(): string
            {
                return 'Test Billing Address';
            }
        };
        $order->shippingAddress = new class
        {
            public function render(): string
            {
                return 'Test Shipping Address';
            }
        };
        $order->shippingAddress->map_url = 'https://www.google.com/maps?q=24.4539,54.3773';
        $order->couponUsages = collect([]);
        $order->sub_total = 100;
        $order->tax = 5;
        $order->total = 105;
        $order->payment_method = 'stripe';
        $order->payment_status = 'paid';
        $order->created_at = now();

        $user = \Mockery::mock(User::class)->makePartial();
        $user->email = 'test@example.com';

        // Trigger notification
        Notification::send($user, new OrderSuccess($order));

        Notification::assertSentTo(
            $user,
            OrderSuccess::class,
            function ($notification, $channels) use ($order, $user) {
                // In the notification, we use $this->order which is the mock
                $mailData = $notification->toMail($user);
                
                $hasReceiptAttachment = false;
                foreach ($mailData->attachments as $attachment) {
                    if (str_contains($attachment['name'], "Receipt-ORD-12345.pdf")) {
                        $hasReceiptAttachment = true;
                        break;
                    }
                }

                return $hasReceiptAttachment && $mailData->view === 'emails.order-success';
            }
        );
    }
}
