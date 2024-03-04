<?php

use App\Board;
use App\Database;
use App\Game;
use App\Hand;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    #[Test]
    public function ifGivenNoValidPlayOrMoveThenTrue()
    {
        $dbMock = Mockery::mock(Database::class);
        $board = new Board();
        $hands = [
            0 => new Hand([]),
            1 => new Hand([]),
        ];
        $currentPlayer = 0;
        $game = new Game($dbMock, -1, $board, $hands, $currentPlayer);

        $pass = $game->willPass();

        $this->assertTrue($pass);
    }
}
