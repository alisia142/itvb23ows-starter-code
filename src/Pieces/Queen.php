<?php

namespace App\Pieces;

use App\Pieces\Piece;

class Queen extends Piece
{
    public function validMove($from, $to): bool
    {
        $board = clone $this->board;
        // is positie to gelijk aan from
        if ($from === $to) {
            return false;
        }
        // verwijder de bijenkoningin van het bord
        $board->removeTile($from);
        // kijk of de bijenkoningin kan schuiven
        if (!$board->slide($from, $to)) {
            return false;
        }
        // controleer de tussenliggende posities
        $currPos = $from;
        [$fromX, $fromY] = explode(',', $from);
        [$toX, $toY] = explode(',', $to);
        while ($currPos != $to) {
            [$currX, $currY] = explode(',', $currPos);
            $next = ($toX <=> $currX) + $currX . ',' . ($toY <=> $currY) + $currY;
            if (!$board->isPositionEmpty($next)) {
                return false;
            }
            $currPos = $next;
        }
        return true;
    }
}
