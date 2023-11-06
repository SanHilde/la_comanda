<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './db/AccesoDatos.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("hola alumnos de los lunes!");
    return $response;
});

// peticiones
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
  });

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos'); // Obtener todos los productos
    $group->get('/{nombre}', \ProductoController::class . ':TraerUno'); // Obtener un producto por nombre
    $group->post('[/]', \ProductoController::class . ':CargarUno'); // Crear un nuevo producto
    $group->put('/{nombre}', \ProductoController::class . ':ModificarUno'); // Modificar un producto por nombre
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno'); // Borrar un producto por ID
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos'); // Ruta para obtener todas las mesas
  $group->get('/{id}', \MesaController::class . ':TraerUno');  // Ruta para obtener una mesa por su ID
  $group->post('[/]', \MesaController::class . ':CargarUno');  // Ruta para cargar una nueva mesa
  $group->put('/{id}', \MesaController::class . ':ModificarUno'); // Ruta para modificar una mesa por su ID
  $group->delete('/{id}', \MesaController::class . ':BorrarUno');  // Ruta para borrar una mesa por su ID
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos'); // Ruta para obtener todos los pedidos
  $group->get('/{id}', \PedidoController::class . ':TraerUno');  // Ruta para obtener un pedido por su ID
  $group->post('[/]', \PedidoController::class . ':CargarUno');  // Ruta para cargar un nuevo pedido
  $group->put('/{id}', \PedidoController::class . ':ModificarUno'); // Ruta para modificar un pedido por su ID
  $group->delete('/{id}', \PedidoController::class . ':BorrarUno');  // Ruta para borrar un pedido por su ID
});


// Run app
$app->run();

