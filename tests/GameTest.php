<?php

use App\Board;
use App\Database;
use App\Game;
use App\Hand;
use App\Ai;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    #[Test]
    public function ifGivenNoValidPlayOrMoveThenTrue()
    {
        $dbMock = Mockery::mock(Database::class);
        $aiMoveMock = Mockery::mock(Ai::class);
        $board = new Board();
        $hands = [
            0 => new Hand([]),
            1 => new Hand([]),
        ];
        $currentPlayer = 0;
        $game = new Game($dbMock, $aiMoveMock, -1, $board, $hands, $currentPlayer, 0, 0);

        $pass = $game->willPass();

        $this->assertTrue($pass);
    }
}
