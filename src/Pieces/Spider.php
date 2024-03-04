<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Spider extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        unset($this->board[$from]);

        if ($from == $to) {
            return false;
        } elseif (!$board->isPositionEmpty($to)) {
            return false;
        } elseif (!$board->isNeighbour($from, $to)) {
            return false;
        } elseif (!$board->getTiles()[$to]) {
            return false;
        }
        
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
