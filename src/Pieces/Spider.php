<?php

namespace App\Piece;

class Spider extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        
        if ($from === $to) {
            return false;
        }
        // Spider kan 3 keer verplaatsen dus haal hem van het bord
        unset($this->board[$from]);
    
        $visited = [];
        $tiles = array($from);
        $tiles[] = null;
    
        // laatst bezochte tile en hoevaak er al is verplaatst
        $previousTile = null;
        $depth = 0;
        while(!empty($tiles) && $depth < 3) {
            // krijg de eerste tile
            $tile = array_shift($tiles);
            if ($tile == null) {
                $depth++;
                $tiles[] = null;
                if (reset($tiles) == null) {
                    break;
                } else {
                    continue;
                }
            }
            // is de tile al bezocht
            if (!in_array($tile, $visited)) {
                $visited[] = $tile;
            }
    
            $b = explode(',', $tile);
    
            foreach($GLOBALS['OFFSETS'] as $pq) {
                $p = $b[0] + $pq[0];
                $q = $b[1] + $pq[1];
    
                $pos = $p.','.$q;
                // is tile al bezocht + pos is niet al geweest + pos is beschikbaar + heeft buren
                if (
                    !in_array($pos, $visited) &&
                    $pos != $previousTile &&
                    !isset($this->board[$pos]) &&
                    hasNeighBour($this->board, $pos)
                ) {
                    if ($pos == $to && $depth == 2) {
                        return true;
                    }
                    $tiles[] = $pos;
                }
                $previousTile = $tile;
            }
            return false;
        }
    }
}
