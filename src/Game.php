<?php

namespace App;

use App\Pieces;

class Game
{
    private int $id;
    private Board $board;
    /** @var Hand[] $hands */
    private array $hands;
    private int $currentPlayer;

    public function __construct(Board $board = null, array $hands = null, int $currentPlayer = 0)
    {
        $this->id = Database::getInstance()->createGame();
        $this->board = $board ?? new Board();
        $this->hands = $hands ?? [0 => new Hand(), 1 => new Hand()];
        $this->currentPlayer = $currentPlayer;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getHands(): array
    {
        return $this->hands;
    }

    public function getCurrentPlayer(): int
    {
        return $this->currentPlayer;
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
                1 => $hands[1]->getPieces(),
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

    public function play($piece, $to): void
    {
        $hand = $this->hands[$this->currentPlayer];

        if (!$hand->hasPiece($piece)) {
            $_SESSION['error'] = "Player does not have tile";
        } elseif (!$this->board->isPositionEmpty($to)) {
            $_SESSION['error'] = 'Board position is not empty';
        } elseif (count($this->board->getTiles()) && !$this->board->hasNeighBour($to)) {
            $_SESSION['error'] = "board position has no neighbour";
        } elseif (
            $this->hands[$this->currentPlayer]->getTotalSum() < 11 &&
            !$this->board->neighboursAreSameColor($player, $to)
        ) {
            $_SESSION['error'] = "Board position has opposing neighbour";
        } elseif ($this->hands[$this->currentPlayer]->getTotalSum() <= 8 && $hand->hasPiece('Q')) {
            $_SESSION['error'] = 'Must play queen bee';
            exit(0);
        } else {
            $this->board->setPosition($to, $this->currentPlayer, $piece);
            $hand->removePiece($piece);
            $this->currentPlayer = 1 - $this->currentPlayer;
            $_SESSION['last_move'] = Database::getInstance()->createMove(
                $this,
                "play",
                $piece,
                $to,
                $_SESSION['last_move'],
            );
        }
    }

    public function move($from, $to): void
    {
        $hand = $this->hands[$this->currentPlayer];
        $piece = new Pieces($this->board);

        if ($this->board->isPositionEmpty($from)) {
            $_SESSION['error'] = 'Board position is empty';
        } elseif ($this->board->isTileOwnedByPlayer($from, $this->currentPlayer)) {
            $_SESSION['error'] = "Tile is not owned by player";
        } elseif ($hand->hasPiece('Q')) {
            $_SESSION['error'] = "Queen bee is not played";
        } else {
            $tile = $this->board->popTile($from);
            if (!$this->board->hasNeighBour($to)) {
                $_SESSION['error'] = "Move would split hive";
            } else {
                $all = $this->board->getAllPositions();
                $queue = [array_shift($all)];
                while ($queue) {
                    $next = explode(',', array_shift($queue));
                    foreach (Board::OFFSETS as $pq) {
                        list($p, $q) = $pq;
                        $p += $next[0];
                        $q += $next[1];
                        if (in_array("$p,$q", $all)) {
                            $queue[] = "$p,$q";
                            $all = array_diff($all, ["$p,$q"]);
                        }
                    }
                }
                if ($all) {
                    $_SESSION['error'] = "Move would split hive";
                } else {
                    if ($from == $to) {
                        $_SESSION['error'] = "Tile must move";
                    } elseif (!$this->board->isPositionEmpty($to) && $tile[1] != "B") {
                        $_SESSION['error'] = "Tile not empty";
                    } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                        if (!$this->board->slide($from, $to)) {
                            $_SESSION['error'] = "Tile must slide";
                        }
                    } elseif ($tile[1] == "G") {
                        if (!$piece->validGrasshopper($from, $to)) {
                            $_SESSION['error'] = "Not a valid grasshopper move";
                        }
                    } elseif ($tile[1] == "A") {
                        if (!$piece->validAnt($from, $to)) {
                            $_SESSION['error'] = "Not a valid ant move";
                        }
                    } elseif ($tile[1] == "S") {
                        if (!$piece->validSpider($from, $to)) {
                            $_SESSION['error'] = "Not a valid spider move";
                        }
                    }
                }
            }
            if (isset($_SESSION['error'])) {
                $this->board->pushTile($from, $tile);
            } else {
                $this->board->pushTile($to, $tile);
                $this->currentPlayer = 1 - $this->currentPlayer;
                $_SESSION['last_move'] = Database::getInstance()->createMove(
                    $this,
                    "move",
                    $from,
                    $to,
                    $_SESSION['last_move'],
                );
            }
        }
    }

    
    public function pass(): Response
    {
        $_SESSION['last_move'] = Database::getInstance()->createPassMove(
            $this,
            $_SESSION['last_move'],
        );
        $this->currentPlayer = 1 - $this->currentPlayer;
    }
}
