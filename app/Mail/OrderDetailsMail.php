<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderDetailsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $products = $this->order->products;
        $total = $products->sum(function ($product) {
            return $product->preco * $product->pivot->quantidade;
        });

        return $this->view('emails.order-details')
                    ->with([
                        'client' => $this->order->client,
                        'products' => $products,
                        'total' => $total
                    ]);
    }
}
