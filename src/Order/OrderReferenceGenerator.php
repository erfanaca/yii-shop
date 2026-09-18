<?php

declare(strict_types=1);

namespace App\Order;

final class OrderReferenceGenerator
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private const LENGTH = 10;

    public function generate(): string
    {
        $reference = '';
        $lastIndex = strlen(self::ALPHABET) - 1;

        for ($i = 0; $i < self::LENGTH; $i++) {
            $reference .= self::ALPHABET[random_int(0, $lastIndex)];
        }

        return $reference;
    }
}
