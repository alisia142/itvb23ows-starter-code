<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Queen extends Piece
{
    /**
     * valid move based on given parameters:
     * - can move 1 step at the time
     * - can't move to starting position
     * - must be played within turn 4
     */
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        if ($from == $to || !$board->isPositionEmpty($to)) {
            return false;
        }
        $board->removeTile($from);
        if (!$board->slide($from, $to)) {
            return false;
        }
        $currPos = $from;
        [$fromX, $fromY] = explode(',', $from);
        [$toX, $toY] = explode(',', $to);
        while ($currPos != $to) {
            [$currX, $currY] = explode(',', $currPos);
            $next = ($toX <=> $currX) + $currX . ',' . ($toY <=> $currY) + $currY;
            if (!$board->isPositionEmpty($next)) {
                return false;
            }
            $currPos = $next;
        }
        return true;
    }
}
