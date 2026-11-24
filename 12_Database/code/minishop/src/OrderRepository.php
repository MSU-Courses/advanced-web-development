<?php

namespace MiniShop;

use PDO;

class OrderRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(int $productId, int $quantity, float $total): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO orders (product_id, quantity, total) VALUES (?, ?, ?)');
        $stmt->execute([$productId, $quantity, $total]);

        return (int) $this->pdo->lastInsertId();
    }
}
