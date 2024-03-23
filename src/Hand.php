<?php

namespace App;

class Hand
{
    private array $pieces = ["Q" => 1,"B" => 2,"S" => 2,"A" => 3,"G" => 3];

    public function __construct(array $pieces = null)
    {
        if(isset($pieces)) {
            $this->pieces = $pieces;
        }
    }

    // returns all pieces
    public function getPieces(): array
    {
        return $this->pieces;
    }

    // returns if pieces includes piece
    public function hasPiece($piece): bool
    {
        return $this->pieces[$piece] > 0;
    }

    // removes piece from pieces
    public function removePiece($piece): void
    {
        $this->pieces[$piece]--;
    }

    // returns number of pieces
    public function getSum(): int
    {
        return array_sum($this->pieces);
    }

    // returns possible pieces
    public function getPossiblePieces(): array
    {
        return array_filter($this->pieces);
    }
}
