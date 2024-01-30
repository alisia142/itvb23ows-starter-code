<?php

session_start();

include_once 'database.php';

$database = new Database();
$player = $_SESSION['player'];
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];
$gameState = new Game($hand, $board, $player);

$db = $database->getDatabase();
$stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
$gameState->setState($result[6]);
header('Location: index.php');
