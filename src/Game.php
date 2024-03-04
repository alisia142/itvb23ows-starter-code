<?php

namespace App;

use App\Pieces;
use App\Exception\InvalidMove;

use Exception;

class Game
{
    private Database $database;
    private int $id;
    private Board $board;
    /** @var Hand[] $hands */
    private array $hands;
    private int $currentPlayer;

    public function __construct(Database $database, int $id = null, Board $board = null, array $hands = null, int $currentPlayer = 0)
    {
        $this->database = $database;
        $this->id = $id ?? $this->database->createGame();
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
    /**
     * @throws InvalidMove
     */
    public function play($piece, $to): void
    {
        $hand = $this->hands[$this->currentPlayer];

        if (!$hand->hasPiece($piece)) {
            throw new InvalidMove("Player does not have tile");
        }
        if ($piece != "Q" && $this->hands[$this->currentPlayer]->getSum() <= 8 && $hand->hasPiece('Q')) {
            throw new InvalidMove("Must play queen bee");
            exit(0);
        }
        [$valid, $err] = $this->validPlay($to);
        if (!$valid) {
            throw new InvalidMove($err);
        } else {
            $this->board->setPosition($to, $this->currentPlayer, $piece);
            $hand->removePiece($piece);
            $this->currentPlayer = 1 - $this->currentPlayer;
            $_SESSION['last_move'] = $this->database->createMove(
                $this,
                "play",
                $piece,
                $to,
                $_SESSION['last_move'],
            );
        }
    }

    public function validPlay($to): array
    {
        $errMessage = null;

        if ($this->board->isPositionEmpty($to)) {
            $errMessage = "Board position is not empty";
        } elseif (count($this->board->getTiles()) && !$this->board->hasNeighBour($to)) {
            $errMessage = "Board position has no neighbour";
        } elseif (
            $this->hands[$this->currentPlayer]->getSum() < 11 &&
            !$this->board->neighboursAreSameColor($player, $to)
        ) {
            $errMessage = "Board position has opposing neighbour";
        }

        return [$errMessage = null, $errMessage];
    }

    public function move($from, $to): void
    {
        [$valid, $err] = $this->validMove($from, $to);
        if (!$valid) {
            throw new InvalidMoveException($err);
        } else {
            if (isset($_SESSION['error'])) {
                $this->board->pushTile($from, $tile);
            } else {
                $this->board->pushTile($to, $tile);
                $this->currentPlayer = 1 - $this->currentPlayer;
                $_SESSION['last_move'] = $this->database->createMove(
                    $this,
                    "move",
                    $from,
                    $to,
                    $_SESSION['last_move'],
                );
            }
        }        
    }
    
    public function validMove($from, $to): array
    {
        $errMessage = null;

        if ($this->board->isPositionEmpty($from)) {
            $errMessage = "Board position is empty";
        } elseif ($this->board->isTileOwnedByPlayer($from, $this->currentPlayer)) {
            $errMessage = "Tile is not owned by player";
        } elseif ($hand->hasPiece('Q')) {
            $errMessage = "Queen bee is not played";
        } elseif ($this->board->hiveSplit($from, $to)) {
            $errMessage = "Move would split hive";
        } else {
            $tile = $this->board->getTileOnPosition($from);
            try {
                $piece = Piece::createPiece($tile[1], $this->board);
                if (!$piece->validMove($from, $to)) {
                    $errMessage = "Not a valid move";
                }
            } catch (Exception $exception) {
                $errMessage = $exception->getMessage();
            }
        }
        return [$errMessage == null, $errMessage];
    }
    
    public function pass(): Response
    {
        $_SESSION['last_move'] = $this->database->createPassMove(
            $this,
            $_SESSION['last_move'],
        );
        $this->currentPlayer = 1 - $this->currentPlayer;
    }

    public function willPass(): bool
    {
        $hand = $this->hands[$this->currentPlayer];
        if (count($hand->getAvailablePieces()) > 0) {
            return false;
        }
        return true;
    }

    public function getAllToPositions(): array
    {
        $to = [];
        foreach (Board::OFFSETS as $pq) {
            foreach ($this->board->getAllPositions() as $pos) {
                $secondPq = explode(',', $pos);
                $to[] = ($pq[0] + $secondPq[0]) . ',' . ($pq[1] + $secondPq[1]);
            }
        }
        // geen duplicates
        $to = array_unique($to);
        // kijk of het board leeg is
        if (!count($this->board->getAllPositions())) {
            $to[] = '0,0';
        }
        return $to;
    }

    public function getPlayPositions(): array
    {
        return array_filter($this->getAllToPositions(), fn($pos) => $this->validPlay($pos)[0]);
    }
}
