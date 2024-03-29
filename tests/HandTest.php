<?php

use App\Hand;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HandTest extends TestCase
{
    #[Test]
    public function testIfHandIsSetCorrectly()
    {
        // arrange
        $hand = new Hand(['Q' => 1]);

        // act
        $hasPiece = $hand->hasPiece("Q");

        // assert
        $this->assertTrue($hasPiece);
    }

    #[Test]
    public function testRemovePieceFromHand()
    {
        // arrange
        $hand = new Hand(["Q" => 1, "B" => 2]);
        
        // act
        $hand->removePiece("Q");

        // assert
        $this->assertFalse($hand->hasPiece("Q"));
    }
}
