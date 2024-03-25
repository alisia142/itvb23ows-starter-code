<?php

use App\Hand;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HandTest extends TestCase
{
    #[Test]
    public function testIfHandIsSetCorrectly()
    {
        $hand = new Hand(['Q' => 1]);

        $this->assertTrue($hand->hasPiece("Q"));
    }

    #[Test]
    public function testRemovePieceFromHand()
    {
        $hand = new Hand(["Q" => 1, "B" => 2]);
        $hand->removePiece("Q");

        $this->assertFalse($hand->hasPiece("Q"));
    }
}
