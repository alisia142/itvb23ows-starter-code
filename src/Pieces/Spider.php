<?php

namespace App\Pieces;

use App\Pieces\Piece;
use App\Board;

class Spider extends Piece
{
    /**
     * valid move based on given parameters:
     * - can move in exactly 3 steps
     * - move is like queen
     * - can't move to starting position
     * - can only move over and to empty tiles
     * - can't move to tile that has been visited
     */
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        $board->removeTile($from);
        if ($from == $to || (!$board->isPositionEmpty($to))) {
            return false;
        } elseif (!$this->destinationReachableInThreeSlides($board, $from, $to)) {
            return false;
        }
        return true;
    }

    private function destinationReachableInThreeSlides($board, $curr, $dest, $visited = [], $count = 0) {
        if ($curr == $dest && $count == 3) {
            return true;
        }
        $neighbours = $board->getNeighbours($curr, fn($neighbour) => $board->isPositionEmpty($neighbour));
        
        foreach ($neighbours as $neighbour) {
            if (in_array($neighbour, $visited)) {
                continue;
            }
            if ($board->slide($curr, $neighbour)) {
                $visited[] = $curr;

                if ($this->destinationReachableInThreeSlides(
                    $board,
                    $neighbour,
                    $dest,
                    $visited,
                    $count+1)
                ) {
                    return true;
                }
            }
        }
        return false;
    }
}
