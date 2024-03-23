<?php

namespace App\Pieces;

use App\Board;
use Exception;

abstract class Piece
{
    /**
     * @throws Exception
     * helper function for creating a new piece, prevents double segments of code
     */
    public static function createPiece($letter, Board $board)
    {
        return match ($letter) {
            'Q' => new Queen($board),
            'B' => new Beetle($board),
            'G' => new Grasshopper($board),
            'A' => new Ant($board),
            'S' => new Spider($board),
            default => throw new Exception("Letter does not exist as piece."),
        };
    }

    protected Board $board;
    
    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    abstract public function validMove($from, $to): bool;
}
