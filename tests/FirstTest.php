<?php

use PHPUnit\Framework\TestCase;

final class FirstTest extends TestCase
{
    public function testIsString(): void
    {
        $value = "Dit is een string";
        $this->assertIsString($value);
    }
}