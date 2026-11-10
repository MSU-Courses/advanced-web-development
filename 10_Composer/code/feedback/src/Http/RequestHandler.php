<?php

declare(strict_types=1);

namespace App\Http;

interface RequestHandler
{
    public function handle(array $input): array;
}
