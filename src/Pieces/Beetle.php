<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Beetle extends Piece
{
    /**
     * valid move based on given parameters:
     * - can move 1 step at the time
     * - move is like queen
     * - can't move to place that is starting position
     * - can only move to and over empty tiles
     * - can move on top of other insects
     */
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        if ($from === $to || (!$board->isPositionEmpty($to))) {
            return false;
        }
        $board->removeTile($from);
        [$fromX, $fromY] = explode(',', $from);
        [$toX, $toY] = explode(',', $to);
        $distanceX = abs($toX - $fromX);
        $distanceY = abs($toY - $fromY);
        if ($distanceX != 1 || $distanceY != 1) {
            return false;
        }
        return true;
    }
}
