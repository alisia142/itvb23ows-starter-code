<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Grasshopper extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        
        if ($from === $to) {
            return false;
        }
        
        if (!$board->isPositionEmpty($to)) {
            return false;
        }

        $neighbours = $board->getNeighbours($from);

        if (!in_array($to, $neighbours)) {
            return false;
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
