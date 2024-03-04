<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Beetle extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;

        $board->removeTile($from);

        if ($from == $to) {
            return false;
        } elseif (!$board->slide($from, $to)) {
            return false;
        }
        return true;
    }
}
