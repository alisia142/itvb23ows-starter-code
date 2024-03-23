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

    // create game by executing insert statement
    public function createGame(): int
    {
        $stmt = $this->connection->prepare('INSERT INTO games VALUES ()');
        $stmt->execute();
        return $stmt->insert_id;
    }

    // find move based on given id
    public function findMoveById($id): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM moves WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    // find all moves based on gameId
    public function findMovesByGame($gameId): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM moves WHERE game_id = ?');
        $stmt->bind_param('i', $gameId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
    }

    // create move by inserting move into moves
    public function createMove($gameId, $type, $from, $to, $lastMoveId): int
    {
        $stmt = $this->connection->prepare(
            'insert into moves (game_id, type, move_from, move_to, previous_id, state)
            values (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('isssis', $gameId, $type, $from, $to, $lastMoveId, $state);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // create pass by inserting pass into moves
    public function createPassMove($gameId, $lastMoveId): int
    {
        $stmt = $this->connection->prepare(
            'insert into moves (game_id, type, move_from, move_to, previous_id, state)
            values (?, "pass", null, null, ?, ?)'
        );
        $stmt->bind_param('iis', $gameId, $lastMoveId, $state);
        $stmt->execute();
        return $stmt->insert_id;
    }
}
