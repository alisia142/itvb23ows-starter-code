<?php

namespace App;

use App\Pieces;
use App\Exception\InvalidMove;

use Exception;

class Game
{
    private Database $database;
    private Ai $aiMove;
    private int $id;
    private Board $board;
    /** @var Hand[] $hands */
    private array $hands;
    private int $currentPlayer;
    private int $moveCounter;
    private ?int $lastMoveId; // ? omdat hij null mag zijn

    public function __construct(
        Database $database,
        Ai $aiMove,
        int $id = null,
        Board $board = null,
        array $hands = null,
        int $currentPlayer = 0,
        int $moveCounter = 0,
        int $lastMoveId = null,
    )
    {
        $this->database = $database;
        $this->aiMove = $aiMove;
        $this->id = $id ?? $this->database->createGame();
        $this->board = $board ?? new Board();
        $this->hands = $hands ?? [0 => new Hand(), 1 => new Hand()];
        $this->currentPlayer = $currentPlayer;
        $this->moveCounter = $moveCounter;
        $this->lastMoveId = $lastMoveId;
    }

    // returns id
    public function getId(): int
    {
        return $this->id;
    }

    // returns board
    public function getBoard(): Board
    {
        return $this->board;
    }

    // returns hands
    public function getHands(): array
    {
        return $this->hands;
    }

    // returns current player
    public function getCurrentPlayer(): int
    {
        return $this->currentPlayer;
    }

    // returns move counter
    public function getMoveCounter(): int
    {
        return $this->moveCounter;
    }

    // returns id of last move
    public function getLastMoveId(): int
    {
        return $this->lastMoveId;
    }

    // create new game object with database and ai
    public static function createFromState(Database $database, Ai $aiMove, string $unserializedState): Game
    {
        $state = unserialize($unserializedState);
        return new Game(
            $database,
            $aiMove,
            $state['id'],
            $state['board'],
            $state['hands'],
            $state['currentPlayer'],
            $state['moveCounter'],
            $state['lastMoveId'],
        );
    }

    // returns current state of game object
    public function getState(): string
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

    // set current state of game object
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
     * executes play based on given parameters
     * if AI is true, skip the validation and force the play of ai
     */
    public function play($piece, $to, $ai = false): void
    {
        $hand = $this->hands[$this->currentPlayer];

        if (!$ai) {
            if (!$hand->hasPiece($piece)) {
                throw new InvalidMove("Player does not have tile");
            }
            if ($piece != "Q" && $this->hands[$this->currentPlayer]->getSum() <= 8 && $hand->hasPiece('Q')) {
                throw new InvalidMove("Must play queen bee");
            }
            [$valid, $err] = $this->validPlay($to);
            if (!$valid) {
                throw new InvalidMove($err);
            }
        }

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
        $this->moveCounter += 1;
    }

    // helper function for play to see if play is valid
    public function validPlay($to): array
    {
        $errMessage = null;

        if (!$this->board->isPositionEmpty($to)) {
            $errMessage = "Board position is not empty";
        } elseif (count($this->board->getTiles()) && !$this->board->hasNeighBour($to)) {
            $errMessage = "Board position has no neighbour";
        } elseif (
            $this->hands[$this->currentPlayer]->getSum() < 11 &&
            !$this->board->neighboursAreTheSameColor($this->currentPlayer, $to)
        ) {
            $errMessage = "Board position has opposing neighbour";
        }

        return [$errMessage == null, $errMessage];
    }

    /**
     * @throws InvalidMove
     * executes move based on given parameters
     * if AI is true, skip the validation and force the move of ai
     */
    public function move($from, $to, $ai = false): void
    {
        if (!$ai) {
            [$valid, $err] = $this->validMove($from, $to);
            if (!$valid) {
                throw new InvalidMoveException($err);
            }
        }
        
        $tile = $this->board->removeTile($from);
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
        $this->moveCounter += 1;
    }
    
    // helper function for move to see if move is valid
    public function validMove($from, $to): array
    {
        $errMessage = null;
        $hand = $this->hands[$this->currentPlayer];

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

    /**
     * @throws InvalidMove
     * executes pass
     * if AI is true, skip the validation and force the pass of ai
     */
    public function pass($ai = false): Response
    {
        if (!$ai && !$this->willPass()) {
            throw new InvalidMove("Player cannot pass");
        }
        $_SESSION['last_move'] = $this->database->createPassMove(
            $this->id,
            $this->lastMoveId,
            $this->getState(),
        );
        $this->currentPlayer = 1 - $this->currentPlayer;
    }

    // helper function for pass to check if player has no more pieces left
    public function willPass(): bool
    {
        $hand = $this->hands[$this->currentPlayer];
        if (count($hand->getPossiblePieces()) > 0) {
            return false;
        }
        return true;
    }
    
    /**
     * @throws InvalidMove
     * executes undo
     * finds last move via database and checks if move can be undone
     */
    public function undo(): void
    {
        $result = $this->database->findMoveById($this->lastMoveId);
        $this->lastMoveId = $result[5];
        if ($this->willUndo()) {
            throw new InvalidMove('Player cannot undo this instance');
        }
        $this->setState($result[6]);
    }

    // helper function for undo to check if moveCounter > 0. If not, move can not be undone
    public function willUndo(): bool
    {
        return $this->moveCounter > 0;
    }

    // returns all "to" positions without duplicates
    public function getAllToPositions(): array
    {
        $to = [];
        foreach (Board::OFFSETS as $pq) {
            foreach ($this->board->getAllPositions() as $pos) {
                $secondPq = explode(',', $pos);
                $to[] = ($pq[0] + $secondPq[0]) . ',' . ($pq[1] + $secondPq[1]);
            }
        }
        $to = array_unique($to);
        if (!count($this->board->getAllPositions())) {
            $to[] = '0,0';
        }
        return $to;
    }

    // returns all possible play positions based on all "to" positions that are valid
    public function getPlayPositions(): array
    {
        return array_filter($this->getAllToPositions(), fn($pos) => $this->validPlay($pos)[0]);
    }

    // creates suggestions from ai and executes play, move or pass based on first element of $move
    public function executeAiMove(): void
    {
        $move = $this->aiMove->createSuggestion($this->moveCounter, $this->hands, $this->board);
        if ($move[0] == 'play') {
            $this->play($move[1], $move[2], true);
        } elseif ($move[0] == 'move') {
            $this->move($move[1], $move[2], true);
        } elseif ($move[0] == 'pass') {
            $this->pass(true);
        } else {
            throw new InvalidMove('Requested move does not exist.');
        }
    }

    // returns the winner of the game based on the board status
    public function returnWinner(): ?int
    {
        $positions = $this->board->getAllPositions();
        $whiteSurrounded = false;
        $blackSurrounded = false;

        foreach($positions as $position) {
            [$currentPlayer, $piece] = $this->board->getTileOnPosition($position);
            if ($currentPlayer !== null) {
                if ($piece == "Q") {
                    if ($this->board->isPlayerSurrounded($currentPlayer, $position)) {
                        if ($currentPlayer == 0) {
                            $whiteSurrounded = true;
                        } else {
                            $blackSurrounded = true;
                        }
                    }
                }
            }
        }

        if ($whiteSurrounded && $blackSurrounded) {
            return -1;
        } else {
            return $currentPlayer;
        }

        return null;
    }
}
