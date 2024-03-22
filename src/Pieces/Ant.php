<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Ant extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        // is positie to gelijk aan from OF is positie op het bord leeg
        if ($from === $to || (!$board->isPositionEmpty($to))) {
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
