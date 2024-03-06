<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Grasshopper extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        
        // is positie to gelijk aan from
        if ($from === $to) {
            return false;
        }
        // is positie to empty
        if (!$board->isPositionEmpty($to)) {
            return false;
        }
        // kijk of er over minstens 1 steen wordt gesprongen
        $neighboursFrom = $board->getNeighbours($from);
        $neighboursTo = $board->getNeighbours($to);

        foreach ($neighboursFrom as $neighbour) {
            if (!in_array($neighbour, $neighboursTo) && !$board->isPositionEmpty($to)) {
                return true;
            }
        }
        // kijk of er diagonaal wordt gesprongen
        $fromC = explode(',', $from);
        $toC = explode(',', $to);
        $dist = abs($toC[0] - $fromC[0]) + abs($toC[1] - $fromC[1]);
        if ($dist != 2) {
            return false;
        }

        return true;
    }
}
