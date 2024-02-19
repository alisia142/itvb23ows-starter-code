<?php

namespace App\Piece;

class Grasshopper extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        
        if ($from === $to) {
            return false;
        }
        $fromExplode = explode(',', $from);
        $toExplode = explode(',', $to);
    
        // Kijk waar de sprinkhaan heen moet
        if ($fromExplode[1] == $toExplode[1]) {
            if ($fromExplode[0] > $toExplode[0]) {
                $offset = [-1,0]; // Sprinkhaan naar links
            } else {
                $offset = [1, 0]; // Sprinkhaan naar rechts
            }
        } elseif ($fromExplode[0] == $toExplode[0]) {
            if ($fromExplode[1] > $toExplode[1]) {
                $offset = [0, -1]; // Sprinkhaan naar boven
            } else {
                $offset = [0, 1]; // Sprinkhaan naar beneden
            }
        } elseif ($fromExplode[1] == $toExplode[1] - ($fromExplode[0] - $toExplode[0])) {
            if ($fromExplode[0] > $toExplode[0]) {
                $offset = [-1, 1]; // Sprinkhaan naar linksbeneden
            } else {
                $offset = [1, -1]; // Sprinkhaan naar rechtsboven
            }
        }
    
        // Positie
        $p = $fromExplode[0] + $offset[0];
        $q = $fromExplode[1] + $offset[1];
    
        $pos = $p.",".$q;
        $posExplode = [$p, $q];
    
        // Kijk of de positie buren heeft
        if (!isset($this->board[$pos])) {
            return false;
        }
        
        // Set pos naar eerst mogelijke positie
        while (isset($this->board[$pos])) {
            $p = $posExplode[0] + $offset[0];
            $q = $posExplode[1] + $offset[1];
    
            $pos = $p.",".$q;
            $posExplode = [$p, $q];
        }
        // Check of positie gelijk is aan de gewenste bestemming
        if ($pos == $to) {
            return true;
        }
        return false;
    }
}
