<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class BoardTest extends TestCase
{
    public static function provider(): array
    {
        return [
            '2 queens' => [
                'tiles' => [
                    '0,0' => [[0, 'Q']],
                    '0,1' => [[1, 'Q']],
                ],
                'currentPlayer' => 0,
            ],
            '2 moves for white, 2 moves for black' => [
                'tiles' => [
                    '0,0' => [[0, 'Q']],
                    '0,1' => [[1, 'Q']],
                    '1,-1' => [[0, 'S']],
                    '-1,-1' => [[1, 'A']],
                ],
                'currentPlayer' => 1,
            ],
        ];
    }

    #[DataProvider('provider')]
    public function testPositionsOwnedByPlayerWithProvider($tiles, $currentPlayer)
    {
        // arrange
        $board = new Board($tiles);
        // act
        $ownedPos = $board->getAllPositionsOwnedByPlayer($currentPlayer);
        // assert
        foreach ($ownedPos as $pos) {
            $this->assertTrue($board->isTileOwnedByPlayer($pos, $currentPlayer));
        }
    }
}