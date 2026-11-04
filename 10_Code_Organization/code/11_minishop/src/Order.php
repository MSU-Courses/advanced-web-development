<?php

declare(strict_types=1);

namespace MiniShop;

use MiniShop\Cart\Cart;
use MiniShop\Delivery\DeliveryMethod;
use MiniShop\Payment\PaymentMethod;

class Order
{
    public function __construct(
        public readonly Cart $cart,
        public readonly DeliveryMethod $delivery,
        public readonly PaymentMethod $payment,
    ) {}

    public function goodsTotal(): int
    {
        return $this->cart->total();
    }

    public function deliveryCost(): int
    {
        return $this->delivery->cost($this->goodsTotal());
    }

    public function paymentFee(): int
    {
        return $this->payment->fee($this->goodsTotal() + $this->deliveryCost());
    }

    public function total(): int
    {
        return $this->goodsTotal() + $this->deliveryCost() + $this->paymentFee();
    }
}
