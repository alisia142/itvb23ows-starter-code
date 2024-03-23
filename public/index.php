<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Ai;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * render_template takes a route name, and parses it with the route, to ensure that the page can be loaded
 * after that, the routes are fetched by routes.php and creates a response with the route based on the path given
 * then, if the route exists, a new database and a new ai is created, and the new controller (with variabeles) is saved in response. 
 * if there is no error, the response will be send.
 */
function render_template(string $name): Response
{
    ob_start();
    include sprintf(__DIR__ . '/../src/pages/%s.php', $name);
    return new Response(ob_get_clean());
}

$url = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($url, PHP_URL_PATH), '/');

$routes = require __DIR__ . '/../src/routes.php';

$response = $routes[$path] ?? new Response('Not Found', 404);

try {
    if (isset($routes[$path])) {
        [$controller, $method] = $routes[$path];
        $database = new Database();
        $aiMove = new Ai(new Client(['base_uri' => "http://0.0.0.0:3030"]));
        $response = (new $controller(database: $database, aiMove: $aiMove))->$method();
    }
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
    print $exception->getMessage();
}

$response->send();
