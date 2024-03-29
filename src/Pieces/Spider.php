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
        if ($from == $to) {
            return false;
        } elseif (!$board->isPositionEmpty($to)) {
            return false;
        } elseif (!$this->destinationPositioninationReachableInThreeSlides($board, $from, $to)) {
            return false;
        }
        return true;
    }

    private function destinationPositioninationReachableInThreeSlides(
        $board,
        $currentPosition,
        $destinationPosition,
        $visited = [],
        $count = 0
    ) {
        if ($currentPosition == $destinationPosition && $count == 3) {
            return true;
        }
        $neighbours = $board->getNeighbours($currentPosition, fn($neighbour) => $board->isPositionEmpty($neighbour));
        
        foreach ($neighbours as $neighbour) {
            if (in_array($neighbour, $visited)) {
                continue;
            }
            if ($board->slide($currentPosition, $neighbour)) {
                $visited[] = $currentPosition;

                if ($this->destinationPositioninationReachableInThreeSlides(
                    $board,
                    $neighbour,
                    $destinationPosition,
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
