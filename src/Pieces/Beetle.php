<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Beetle extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        // is positie to gelijk aan from OF is positie op het bord leeg
        if ($from === $to || (!$board->isPositionEmpty($to))) {
            return false;
        }
        // verwijder de kever van het bord
        $board->removeTile($from);
        // kijk of de kever maar 1 stap zet
        [$fromX, $fromY] = explode(',', $from);
        [$toX, $toY] = explode(',', $to);
        $distanceX = abs($toX - $fromX);
        $distanceY = abs($toY - $fromY);
        if ($distanceX != 1 || $distanceY != 1) {
            return false;
        }
        return true;
    }
}
