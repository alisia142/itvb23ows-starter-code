<?php

namespace App\Controller;

use App\Database;
use App\Game;
use App\Exception\InvalidMove;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    private Database $database;

    public function __construct(Database $database) 
    {
        $this->database = $database;
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

        $game = $_SESSION['game'];
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

        $game = $_SESSION['game'];
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

        $_SESSION['game'] = new Game();

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

        $game = $_SESSION['game'];
        $game->pass();

        return new RedirectResponse("/");
    }
}
