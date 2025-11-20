<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $itemName;
    public $quantity;

    public function __construct($itemName, $quantity)
    {
        $this->itemName = $itemName;
        $this->quantity = $quantity;
    }

    public function build()
    {
        return $this->subject('Low Stock Alert')
                    ->view('emails.low_stock');
    }
}
