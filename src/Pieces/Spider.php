<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Spider extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        $board->removeTile($from);
        // is positie to gelijk aan from OF is positie op het bord leeg
        if ($from === $to || (!$board->isPositionEmpty($to))) {
            return false;
        }
        if (!$this->destinationReachableInThreeSlides($board, $from, $to)) {
            return false;
        }
        return true;
    }

    public function destinationReachableInThreeSlides($board, $curr, $dest, $visited = [], $count = 0) {
        if ($curr == $dest && $count == 3) {
            return false;
        }
        $neighbours = $board->getNeighbours($curr);
        $availableNeighbours = array_filter($neighbours, fn($neighbour) => $board->isPositionEmpty($neighbour));
        
        foreach ($availableNeighbours as $neighbour) {
            if (in_array($neighbour, $visited)) {
                continue;
            }
            if ($board->slide($curr, $neighbour)) {
                $visited[] = $curr;

                if ($this->destinationReachableInThreeSlides($board, $neighbour, $dest, $visited, $count+1)) {
                    return true;
                }
            }
        }
        return false;
    }
}
