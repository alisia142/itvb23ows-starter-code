<?php

class Game {
    private $hand;
    private $board;
    private $player;

    public function __construct($hand, $board, $player) {
        $this->hand = $hand;
        $this->board = $board;
        $this->player = $player;
    }

    public function getState() {
        return serialize([$this->hand, $this->board, $this->player]);
    }

    public function setState($state) {
        list($hand, $board, $player) = unserialize($state);
        $this->hand = $hand;
        $this->board = $board;
        $this->player = $player;
    }

    public function getHand() {
        return $this->hand;
    }

    public function getBoard() {
        return $this->board;
    }

    public function getPlayer() {
        return $this->player;
    }
}

class Database {
    private $db;
    
    public function __construct() {
        $this->db = new mysqli($_ENV['PHP_MYSQL_HOSTNAME'], 'root', $_ENV['MYSQL_ROOT_PASSWORD'], 'hive');
    }

    public function getDatabase() {
        return $this->db;
    }
}
