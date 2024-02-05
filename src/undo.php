<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;

session_start();

$player = $_SESSION['player'];
/** @var Board $board */
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];

$db = include_once 'database.php';
$stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
$gameState->setState($result[6]);
header('Location: index.php');
