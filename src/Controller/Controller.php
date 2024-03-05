<?php

namespace App\Controller;

use App\Database;
use App\Game;
use App\Ai;
use App\Exception\InvalidMove;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    private Database $database;
    private Ai $aiMove;

    public function __construct(Database $database, Ai $aiMove) 
    {
        $this->database = $database;
        $this->aiMove = $aiMove;
    }
    public function index(): Response
    {
        ob_start();
        include __DIR__ . '../index.php';
        $content = ob_get_clean();
        return new Response($content);
    }

    public function play(): Response
    {
        session_start();

        $piece = $_POST['piece'];
        $to = $_POST['to'];

        $game = Game::createFromState($this->database, $this->aiMove, $_SESSION['game']);
        try {
            $game->play($piece, $to);
        } catch (InvalidMove $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }
        

        return new RedirectResponse("/");
    }

    public function move(): Response
    {
        session_start();

        $from = $_POST['from'];
        $to = $_POST['to'];

        unset($_SESSION['error']);

        $game = Game::createFromState($this->database, $this->aiMove, $_SESSION['game']);
        try {
            $game->move($from, $to);
        } catch (InvalidMove $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }
        

        return new RedirectResponse("/");
    }

    public function restart(): Response
    {
        session_start();

        $game = new Game($this->database);
        $_SESSION['game'] = $game->getState();

        return new RedirectResponse("/");
    }

    public function undo(): Response
    {
        session_start();

        $result = Database::getInstance()->findMoveById($_SESSION['last_move']);
        try {
            $game->undo();
        } catch (InvalidMove $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }
        $_SESSION['game'] = $game->getState();

        return new RedirectResponse("/");
    }

    public function pass(): Response
    {
        session_start();

        $game = Game::createFromState($this->database, $this->aiMove, $_SESSION['game']);
        try {
            $game->pass();
        } catch (InvalidMove $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }

        return new RedirectResponse("/");
    }

    public function aiMove(): Response
    {
        session_start();

        $game = Game::createFromState($this->database, $this->aiMove, $_SESSION['game']);
        $game->executeAiMove();
        $_SESSION['game'] = $game->getState();

        return new RedirectResponse("/");
    }
}
