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
        // arrange
        $dbMock = Mockery::mock(Database::class);
        $aiMoveMock = Mockery::mock(Ai::class);
        $board = new Board();
        $hands = [
            0 => new Hand([]),
            1 => new Hand([]),
        ];
        $currentPlayer = 0;
        $game = new Game($dbMock, $aiMoveMock, -1, $board, $hands, $currentPlayer, 0, 0);
        
        // act
        $pass = $game->willPass();

        // assert
        $this->assertTrue($pass);
    }

    #[Test]
    public function testMoveCounterIncreasedIfPieceIsPlayed()
    {
        // arrange
        $dbMock = Mockery::mock(Database::class);
        $dbMock->allows('createMove')->andReturns(1);
        $aiMoveMock = Mockery::mock(Ai::class);
        $board = new Board();
        $hands = [
            0 => new Hand(['Q' => 1,]),
            1 => new Hand(['Q' => 1,]),
        ];
        $currentPlayer = 0;
        $game = new Game($dbMock, $aiMoveMock, -1, $board, $hands, $currentPlayer, 0, 0);

        // act
        $game->play('Q', '0,0');
        $moveCounter = $game->getMoveCounter();

        // assert
        $this->assertEquals(1, $moveCounter);
    }

    #[Test]
    public function testAiMoveIsCalledWithAiSuggestion()
    {
        // arrange
        $dbMock = Mockery::mock(Database::class);
        $dbMock->allows('createMove')->andReturns(1);
        $aiMoveMock = Mockery::mock(Ai::class);
        $aiMoveMock->allows('createSuggestion')->andReturns(['play', 'Q', '0,0']);
        $board = new Board();
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $moveCounter = 0;
        $game = new Game($dbMock, $aiMoveMock, -1, $board, $hands, $currentPlayer, $moveCounter);

        // act
        $game->executeAiMove();

        // assert
        $dbMock->shouldHaveReceived('createMove', [-1, 'play', 'Q', '0,0', Mockery::any(), Mockery::any()]);
        $this->assertTrue(Mockery::getContainer()->mockery_getExpectationCount() > 0); // extra assertion to check if expected outcome > 0
    }
}
