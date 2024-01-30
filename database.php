<?php

// function get_state() {
//     return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
// }

// function set_state($state) {
//     list($a, $b, $c) = unserialize($state);
//     $_SESSION['hand'] = $a;
//     $_SESSION['board'] = $b;
//     $_SESSION['player'] = $c;
// }

// return new mysqli('db', 'root', '', 'hive');

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
        $this->db = new mysqli('db', 'root', '', 'hive');
    }

    public function getDatabase() {
        return $this->db;
    }
}