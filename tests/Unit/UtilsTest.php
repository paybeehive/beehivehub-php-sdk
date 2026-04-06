<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit;

use BeehiveHub\SDK\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testGenerateIdReturnsCorrectLength(): void
    {
        foreach ([5, 10, 20, 32] as $length) {
            $this->assertSame($length, strlen(Utils::generateId($length)));
        }
    }

    public function testGenerateIdDefaultIsLowercaseAlphanumeric(): void
    {
        $id = Utils::generateId(100);

        $this->assertMatchesRegularExpression('/^[0-9a-z]+$/', $id);
    }

    public function testGenerateIdUppercaseIncludesUppercaseChars(): void
    {
        // With 100 chars the probability of no uppercase is astronomically low
        $id = Utils::generateId(100, true);

        $this->assertMatchesRegularExpression('/[A-Z]/', $id);
    }

    public function testGenerateIdUppercaseOnlyAlphanumeric(): void
    {
        $id = Utils::generateId(100, true);

        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]+$/', $id);
    }

    public function testGenerateIdReturnsDifferentValuesOnSubsequentCalls(): void
    {
        $a = Utils::generateId(20);
        $b = Utils::generateId(20);

        // Statistically impossible to be equal with length 20
        $this->assertNotSame($a, $b);
    }
}
