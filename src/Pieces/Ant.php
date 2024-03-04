<?php

namespace App\Piece;

class Ant extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        
        if ($from === $to) {
            return false;
        }
        // Ant kan onbeperkt aantal keer verplaatsen dus haal hem van het bord
        unset($this->board[$from]);
    
        // Kijk of de ant hetzelfde mag als de bijenkoningin
        if ($board->slide($from, $to)) {
            return true;
        }
        
        return false;
    }
}
