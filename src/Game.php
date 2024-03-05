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
    private int $moveCounter;
    private ?int $lastMoveId; // ? omdat hij null mag zijn

    public function __construct(
        Database $database,
        int $id = null,
        Board $board = null,
        array $hands = null,
        int $currentPlayer = 0,
        int $moveCounter = 0,
        int $lastMoveId = null
    )
    {
        $this->database = $database;
        $this->id = $id ?? $this->database->createGame();
        $this->board = $board ?? new Board();
        $this->hands = $hands ?? [0 => new Hand(), 1 => new Hand()];
        $this->currentPlayer = $currentPlayer;
        $this->moveCounter = $moveCounter;
        $this->lastMoveId = $lastMoveId;
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

    public function getMoveCounter(): int
    {
        return $this->moveCounter;
    }

    public function getLastMoveId(): int
    {
        return $this->lastMoveId;
    }

    public function createFromState(Database $database, string $unserializedState): Game
    {
        $state = unserialize($unserializedState);
        return new Game(
            $database,
            $state['id'],
            $state['board'],
            $state['hands'],
            $state['currentPlayer'],
            $state['moveCounter'],
            $state['lastMoveId'],
        );
    }

    public static function getState(): string
    {
        return serialize([
            'id' => $this->id,
            'board' => $this->board,
            'hands' => $this->hands,
            'currentPlayer' => $this->currentPlayer,
            'moveCounter' => $this->moveCounter,
            'lastMoveId' => $this->lastMoveId,
        ]);
    }

    public static function setState($unserializedState): void
    {
        $state = serialize($unserializedState);
        $this->id = $state['id'];
        $this->board = $state['board'];
        $this->hands = $state['hands'];
        $this->currentPlayer = $state['currentPlayer'];
        $this->moveCounter = $state['moveCounter'];
        $this->lastMoveId = $state['lastMoveId'];
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
        }
        [$valid, $err] = $this->validPlay($to);
        if (!$valid) {
            throw new InvalidMove($err);
        } else {
            $this->board->setPosition($to, $this->currentPlayer, $piece);
            $hand->removePiece($piece);
            $this->currentPlayer = 1 - $this->currentPlayer;
            $this->lastMoveId = $this->database->createMove(
                $this->id,
                "play",
                $piece,
                $to,
                $this->lastMoveId,
                $this->getState(),
            );
        }
        $this->moveCounter += 1;
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
            !$this->board->neighboursAreTheSameColor($player, $to)
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
                $this->lastMoveId = $this->database->createMove(
                    $this->id,
                    "move",
                    $from,
                    $to,
                    $this->lastMoveId,
                    $this->getState(),
                );
            }
        }
        $this->moveCounter += 1;
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
            $this->id,
            $this->lastMoveId,
            $this->getState(),
        );
        $this->currentPlayer = 1 - $this->currentPlayer;
    }

    public function willPass(): bool
    {
        $hand = $this->hands[$this->currentPlayer];
        if (count($hand->getPossiblePieces()) > 0) {
            return false;
        }
        return true;
    }
    
    public function undo(): void
    {
        $result = $this->database->findMoveById($this->lastMoveId);
        $this->lastMoveId = $result[5];
        if ($this->willUndo()) {
            throw new InvalidMove('Player cannot undo this instance');
        }
        $this->setState($result[6]);
    }

    public function willUndo(): bool
    {
        return $this->moveNumber > 0;
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
