<?php

namespace App\Piece;

class Pieces
{
    function validGrasshopper($board, $from, $to) {
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
        if (!isset($board[$pos])) {
            return false;
        }
        
        // Set pos naar eerst mogelijke positie
        while (isset($board[$pos])) {
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
    
    function validAnt($board, $from, $to) {
        if ($from === $to) {
            return false;
        }
        // Ant kan onbeperkt aantal keer verplaatsen dus haal hem van het bord
        unset($board[$from]);
    
        // Kijk of de ant hetzelfde mag als de bijenkoningin
        if (slide($board, $from, $to)) {
            return true;
        }
        
        return false;
    }
    
    function validSpider($board, $from, $to) {
        if ($from === $to) {
            return false;
        }
        // Spider kan 3 keer verplaatsen dus haal hem van het bord
        unset($board[$from]);
    
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
                if (!in_array($pos, $visited) && $pos != $previousTile && !isset($board[$pos]) && hasNeighBour($board, $pos)) {
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