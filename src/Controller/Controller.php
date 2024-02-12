<?php

namespace App\Controller;

use App\Database;
use App\Game;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    public function index(): Response
    {
        return render_template("index");
    }

    public function play(): Response
    {
        session_start();

        $piece = $_POST['piece'];
        $to = $_POST['to'];

        $game = $_SESSION['game'];
        $game->play($piece, $to);

        return new RedirectResponse("/");
    }

    public function move(): Response
    {
        session_start();

        $from = $_POST['from'];
        $to = $_POST['to'];

        unset($_SESSION['error']);

        $game = $_SESSION['game'];
        $game->move($from, $to);        

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
        $_SESSION['last_move'] = $result[5];
        $game = $_SESSION['game'];
        $game->setState($result[6]);

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