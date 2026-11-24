<?php

namespace MiniShop;

use PDO;
use RuntimeException;
use Throwable;

class OrderService
{
    public function __construct(
        private PDO $pdo,
        private ProductRepository $products,
        private OrderRepository $orders,
    ) {
    }

    public function place(int $productId, int $quantity): int
    {
        $this->pdo->beginTransaction();

        try {
            $product = $this->products->find($productId);
            if ($product === null) {
                throw new RuntimeException('Товар не найден');
            }

            if (!$this->products->decreaseStock($productId, $quantity)) {
                throw new RuntimeException('Недостаточно товара на складе');
            }

            $orderId = $this->orders->create($productId, $quantity, $product['price'] * $quantity);

            $this->pdo->commit();

            return $orderId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
