<?php

use PHPUnit\Framework\TestCase;

final class StringTest extends TestCase
{
    public function ttestIsString(): void
    {
        $value = "Dit is een string";
        $this->assertIsString($value);
    }
}