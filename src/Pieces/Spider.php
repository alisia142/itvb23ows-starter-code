<?php

namespace App\Piece;

use App\Pieces\Piece;

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
                // reset array en geef het eerste element terug
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
    
            foreach($this->getAllNeighbours($tile) as $neighbour) {
                // is tile al bezocht + pos is niet al geweest + pos is beschikbaar + heeft buren
                if (
                    !in_array($neighbour, $visited) &&
                    $neighbour != $previousTile &&
                    $this->isPositionEmpty(neighbour) &&
                    $this->hasNeighbour($neighbour)
                ) {
                    if ($neighbour == $to && $depth == 2) {
                        return true;
                    }
                    $tiles[] = $neighbour;
                }
                $previousTile = $tile;
            }
        }
        return false;
    }
}
