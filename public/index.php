<?php
// set up composer autoloader
require_once("../vendor/autoload.php");

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Laminas\Diactoros\Response\RedirectResponse;

use App\Models\Blog;
use App\Controllers\IndexController;
use App\Controllers\UsersController;
use App\Controllers\PagesController;
use Dotenv\Dotenv;

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DBHOST'],
    'database' => $_ENV['DBNAME'],
    'username' => $_ENV['DBUSER'],
    'password' => $_ENV['DBPASS'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// create a server request object
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

// create the router container and get the routing map
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$map->get(  'home', '/', [
            'controller'=>'App\Controllers\IndexController',
            'action'=>'indexAction'
]);

$map->get(  'about', '/about', [
            'controller'=>'App\Controllers\PagesController',
            'action'=>'aboutAction'
]);

$map->get(  'contact', '/contact', [
            'controller'=>'App\Controllers\PagesController',
            'action'=>'contactAction'
]);

$map->post( 'contactSend', '/contact', [
            'controller'=>'App\Controllers\PagesController',
            'action'=>'contactActionSend'
]);

$map->get(  'showBlog', '/blog/{id}', [
    'controller'=>'App\Controllers\BlogController',
    'action'=>'showBlogAction'
]);

// add a route to the map, and a handler for it
$map->get('blog.read', '/blog/{id}', function ($request) {
    $id = (int) $request->getAttribute('id');
    $response = new Laminas\Diactoros\Response();
    $response->getBody()->write("You asked for blog entry {$id}.");
    return $response;
});

// get the route matcher from the container ...
$matcher = $routerContainer->getMatcher();

// .. and try to match the request to a route.
$route = $matcher->match($request);
if (! $route) {
    echo "No route found for the request.";
    exit;
} else {
    //Aprovachmos la posibilidad que nos da php de crear clases con el nombre almacenado en una variable
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];

    $needsAuth = $handlerData['auth'] ?? false;
    $sessionUserId = $_SESSION['userId'] ?? null;

    if ($needsAuth && !$sessionUserId) {
        header('Location: /login');

    }
    else {
        $controller = new $controllerName;
        $response = $controller->$actionName($request);
        foreach($response->getHeaders() as $name => $values) {
            foreach($values as $value) {
              header(sprintf('%s: %s', $name, $value), false);
              }
         }
         http_response_code($response->getStatusCode());
         echo $response->getBody();
    }
}

// // add route attributes to the request
// foreach ($route->attributes as $key => $val) {
//     $request = $request->withAttribute($key, $val);
// }

// // dispatch the request to the route handler.
// // (consider using https://github.com/auraphp/Aura.Dispatcher
// // in place of the one callable below.)
// $callable = $route->handler;
// $response = $callable($request);

// // emit the response
// foreach ($response->getHeaders() as $name => $values) {
//     foreach ($values as $value) {
//         header(sprintf('%s: %s', $name, $value), false);
//     }
// }
// http_response_code($response->getStatusCode());
// echo $response->getBody();