<?php

declare(strict_types=1);

trait HasDeliveryStatus
{
    private string $status = 'создано';

    public function send(): void
    {
        $this->status = 'в пути';
    }

    public function complete(): void
    {
        $this->status = 'доставлено';
    }

    public function status(): string
    {
        return $this->status;
    }
}

final class CourierDelivery
{
    use HasDeliveryStatus;
}

final class PostDelivery
{
    use HasDeliveryStatus;
}

$courier = new CourierDelivery();
$post = new PostDelivery();

$courier->send();
echo $courier->status() . "\n"; // в пути
echo $post->status() . "\n";    // создано

$post->send();
$post->complete();
echo $post->status() . "\n";    // доставлено
