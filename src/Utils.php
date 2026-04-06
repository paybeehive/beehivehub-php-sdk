<?php

declare(strict_types=1);

namespace BeehiveHub\SDK;

class Utils
{
    public static function generateId(int $length, bool $uppercase = false): string
    {
        $chars  = $uppercase
            ? '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
            : '0123456789abcdefghijklmnopqrstuvwxyz';
        $max    = strlen($chars) - 1;
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, $max)];
        }

        return $result;
    }
}
