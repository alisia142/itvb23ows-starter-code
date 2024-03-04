<?php

namespace App\Piece;

use App\Piece;

class Queen extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        unset($this->board[$from]);
        if ($from == $to) {
            return false;
        } elseif (!$board->isPositionEmpty($to)) {
            return false;
        } elseif (!$board->slide($from, $to)) {
            return false;
        }
        return true;
    }
}
