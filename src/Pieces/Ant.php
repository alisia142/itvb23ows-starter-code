<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Ant extends Piece
{
    /**
     * valid move based on given parameters:
     * - can move unlimited times
     * - move is like queen
     * - can't move to place that is starting position
     * - can only move to and over empty tiles
     */
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        if ($from == $to || (!$board->isPositionEmpty($to))) {
            return false;
        }
        $neighbours = $board->getNeighbours($from);
        if (!in_array($to, $neighbours)) {
            return false;
        }
        if ($board->slide($from, $to)) {
            return true;
        }
        return false;
    }
}
