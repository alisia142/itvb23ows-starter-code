<?php

session_start();

include_once 'util.php';
include_once 'database.php';

$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];

$gameState = new Game($hand, $board, $player);
$database = new Database();

$db = $database->getDatabase();
$stmt = $db->prepare(
    'insert into moves (game_id, type, move_from, move_to, previous_id, state)
    values (?, "pass", null, null, ?, ?)'
);
$stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], $gameState->getState());
$stmt->execute();
$_SESSION['last_move'] = $db->insert_id;
$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');
