<?php

namespace App\Notifications;

use App\Models\ProductUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceDropNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ProductUrl $productUrl,
        public int $oldPriceCents,
        public int $newPriceCents,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $productName = $this->productUrl->product->name;
        $oldPrice = number_format($this->oldPriceCents / 100, 2);
        $newPrice = number_format($this->newPriceCents / 100, 2);
        $drop = number_format(($this->oldPriceCents - $this->newPriceCents) / 100, 2);

        return (new MailMessage)
            ->subject("Price Drop: {$productName}")
            ->greeting('Good news!')
            ->line("The price for **{$productName}** has dropped.")
            ->line("Previous price: {$oldPrice}")
            ->line("New price: **{$newPrice}** (down {$drop})")
            ->action('View Product', $this->productUrl->url)
            ->line('Happy shopping!');
    }
}
