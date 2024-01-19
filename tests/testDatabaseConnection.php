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
}