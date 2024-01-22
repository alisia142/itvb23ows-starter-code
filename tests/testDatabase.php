<?php
use PHPUnit\Framework\TestCase;

final class DBTest extends TestCase {
    public function testCreatedDatabase(): void
    {
        $connection = new mysqli('db', 'root', '', 'hive');
        if ($connection->connect_error) {
            die("Connection Failed: " . $connection->connect_error);
        } 
        echo "Connected Successfully";
    }

    public function testAddMove(): void
    {
        $connection = new mysqli('db', 'root', '', 'hive');
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        $stmt = $connection->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) 
        values (?, "move", ?, ?, ?, ?)');
        $stmt->bind_param('issis', 1, '0,0', '1,0', 2, 'BBSSAAAGGG');
        $stmt->execute();
    }
}