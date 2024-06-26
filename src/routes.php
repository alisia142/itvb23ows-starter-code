<?php

namespace App;

use App\Controller\Controller;

$routes = [
    '' => [Controller::class, 'index'],
    'play' => [Controller::class, 'play'],
    'move' => [Controller::class, 'move'],
    'pass' => [Controller::class, 'pass'],
    'restart' => [Controller::class, 'restart'],
    'undo' => [Controller::class, 'undo'],
    'ai' => [Controller::class, 'aiMove'],
];

return $routes;
