<?php
// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Factory\AppFactory;
// use Psr\Http\Server\RequestHandlerInterface;
// use Slim\Routing\RouteCollectorProxy;
// use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
error_reporting(-1);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/LoggerMiddleware.php';
require_once './middlewares/AuthMiddleware.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();


$app->get('/', function (Request $request, Response $response, $args) {
$payload = json_encode(array("mensaje" => "Bienvenido a La Comandita"));
sleep(2);
$response->getBody()->write($payload);
return $response->withHeader('Content-Type', 'application/json');
})->add(new LoggerMiddleware());


// peticiones
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
    $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
    $group->put('/{usuario}', \UsuarioController::class . ':ModificarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
    $group->delete('/{usuario}', \UsuarioController::class . ':BorrarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
  });

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos')->add(AuthMiddleware::class .":authMozo")->add(new LoggerMiddleware());
    $group->get('/{nombre}', \ProductoController::class . ':TraerUno')->add(AuthMiddleware::class .":authMozo")->add(new LoggerMiddleware());
    $group->post('[/]', \ProductoController::class . ':CargarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
    $group->put('/{nombre}', \ProductoController::class . ':ModificarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
  $group->get('/{id}', \MesaController::class . ':TraerUno')->add(AuthMiddleware::class .":authMozo")->add(new LoggerMiddleware());
  $group->post('[/]', \MesaController::class . ':CargarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
  $group->put('/{id}', \MesaController::class . ':ModificarUno')->add(AuthMiddleware::class .":authMozo")->add(new LoggerMiddleware());
  $group->delete('/{id}', \MesaController::class . ':BorrarUno')->add(AuthMiddleware::class .":authSocio")->add(new LoggerMiddleware());
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(new LoggerMiddleware());
  $group->get('/{id}', \PedidoController::class . ':TraerUno');  // Ruta para obtener un pedido por su ID
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(AuthMiddleware::class .":authMozo")->add(new LoggerMiddleware());
  $group->put('/{id}', \PedidoController::class . ':ModificarUno');
  $group->delete('/{id}', \PedidoController::class . ':BorrarUno')->add(AuthMiddleware::class .":authMozo")->add(new LoggerMiddleware());
});


// Run app
$app->run();

