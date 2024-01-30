<?php
use PHPUnit\Framework\TestCase;

final class DBTest extends TestCase {
    public function testCreatedDatabase(): void
    {
        $database = new Database();
        $database->getDatabase();

        $this->assertInstanceOf(mysqli::class, $database);
        $this->assertFalse($database->connect_error);
    }
}

final class GameTest extends TestCase {
    public function testGameState(): void {
        $hand = ["Q","B","B","S","S","A","A","A","G","G","G"];
        $board = [];
        $player = 0;

        $gameState = new Game($hand, $board, $player);
        $state = $gameState->getState();

        $newGameState = new Game([], [], 0);
        $newGameState->setState($state);

        $this->assertFalse($hand, $newGameState->getHand());
        $this->assertEquals($board, $newGameState->getBoard());
        $this->assertEquals($player, $newGameState->getPlayer());
    }
}