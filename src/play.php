<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;
use App\Hand;
use App\Database;

session_start();

$piece = $_POST['piece'];
$to = $_POST['to'];

$player = $_SESSION['player'];
/** @var Board $board */
$board = $_SESSION['board'];
/** @var Hand $hand */
$hand = $_SESSION['hand'][$player];

if (!$hand->hasPiece($piece)) {
    $_SESSION['error'] = "Player does not have tile";
} elseif (!$board->isPositionEmpty($to)) {
    $_SESSION['error'] = 'Board position is not empty';
} elseif (count($board->getTiles()) && !$board->hasNeighBour($to)) {
    $_SESSION['error'] = "board position has no neighbour";
} elseif ($hand->getSum() < 11 && !$board->neighboursAreSameColor($player, $to)) {
    $_SESSION['error'] = "Board position has opposing neighbour";
} elseif ($hand->getSum() <= 8 && $hand->hasPiece('Q')) {
    $_SESSION['error'] = 'Must play queen bee';
    exit(0);
} else {
    $board->setPosition($to, $_SESSION['player'], $piece);
    $hand->removePiece($piece);
    $_SESSION['player'] = 1 - $_SESSION['player'];
    $_SESSION['last_move'] = Database::getInstance()->createMove(
        $_SESSION['game_id'],
        "play",
        $piece,
        $to,
        $_SESSION['last_move'],
    );
}

header('Location: index.php');
