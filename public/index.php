<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Ai;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

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
