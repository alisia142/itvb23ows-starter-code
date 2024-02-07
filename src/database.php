<?php

namespace App;

use mysqli;
use mysqli_result;

class Database
{
    private static ?Database $instance;

    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function getState(): string
    {
        /** @var Board $board */
        $board = $_SESSION['board'];
        /** @var Hand[] $hands */
        $hands = $_SESSION['hand'];
        return serialize([
            [
                0 => $hands[0]->getPieces(),
                1 => $hand[1]->getPieces(),
            ], 
            $board->getTiles(), 
            $_SESSION['player']
        ]);
    }

    public static function setState($state): void
    {
        list($a, $b, $c) = unserialize($state);
        $_SESSION['hand'] = [
            0 => new Hand($a[0]),
            1 => new Hand($a[1]),
        ];
        $_SESSION['board'] = new Board($b);
        $_SESSION['player'] = $c;
    }

    private mysqli $connection;

    public function __construct()
    {
        $this->connection = new mysqli(
            $_ENV['PHP_MYSQL_HOSTNAME'],
            'root',
            $_ENV['MYSQL_ROOT_PASSWORD'],
            $_ENV['MYSQL_DATABASE'],
        );
    }

    public function createGame(): int
    {
        $stmt = $this->connection->prepare('INSERT INTO games VALUES ()');
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function findMoveById($id): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM moves WHERE id = ' . $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    public function findMoveByGame($gameId): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM moves WHERE game_id = ' . $gameId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
    }

    public function createMove($gameId, $type, $from, $to, $lastMoveId): int
    {
        $stmt = $this->connection->prepare(
            'insert into moves (game_id, type, move_from, move_to, previous_id, state)
            values (?, "move", ?, ?, ?, ?)'
        );
        $state = self::getState();
        $stmt->bind_param('isssis', $gameId, $type, $from, $to, $lastMoveId, $state);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function createPassMove($gameId, $lastMoveId): int
    {
        $stmt = $this->connection->prepare(
            'insert into moves (game_id, type, move_from, move_to, previous_id, state)
            values (?, "pass", null, null, ?, ?)'
        );
        $state = self::getState();
        $stmt->bind_param('iis', $gameId, $lastMoveId, $state);
        $stmt->execute();
        return $stmt->insert_id;
    }
}
