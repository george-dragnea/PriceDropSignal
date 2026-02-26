<?php

use App\Models\ProductUrl;
use App\Notifications\PriceDropNotification;

test('notification contains product name and prices', function () {
    $url = ProductUrl::factory()->create();
    $user = $url->product->user;
    $notification = new PriceDropNotification($url, 5000, 3999);

    $mail = $notification->toMail($user);

    expect($mail->subject)->toContain('Price Drop');
    expect($mail->subject)->toContain($url->product->name);
});

test('notification is sent via mail', function () {
    $url = ProductUrl::factory()->create();
    $notification = new PriceDropNotification($url, 5000, 3999);

    expect($notification->via($url->product->user))->toBe(['mail']);
});
