<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Grasshopper extends Piece
{
    /**
     * valid move based on given parameters:
     * - can move in a straight line
     * - can't move to place that is starting position
     * - must move over at least one stone
     * - can't move to occupied place
     * - can't move over empty tiles
     */
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;

        if ($from == $to || !$board->isPositionEmpty($to)) {
            return false;
        }

        $neighboursFrom = $board->getNeighbours($from);
        $neighboursTo = $board->getNeighbours($to);

        foreach ($neighboursFrom as $neighbour) {
            if (!in_array($neighbour, $neighboursTo) && !$board->isPositionEmpty($to)) {
                return true;
            }
        }

        $fromC = explode(',', $from);
        $toC = explode(',', $to);
        $dist = abs($toC[0] - $fromC[0]) + abs($toC[1] - $fromC[1]);
        if ($dist != 2) {
            return false;
        }

        return true;
    }
}
