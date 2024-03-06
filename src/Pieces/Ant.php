<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Ant extends Piece
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
        $neighbours = $board->getNeighbours($from);
        if (!in_array($to, $neighbours)) {
            return false;
        }
        // kijk of de mier kan schuiven naar zijn bestemming
        if ($board->slide($from, $to)) {
            return true;
        }
        
        return false;
    }
}
