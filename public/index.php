<?php
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
require_once './controllers/EncuestaController.php';
require_once './controllers/ArchivoController.php';
require_once './controllers/LogInController.php';
require_once './controllers/LogController.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/SectorMiddleware.php';
require_once './middlewares/AuthMiddleware.php';
require_once './utils/AutentificadorJWT.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();


$app->group('/', function (RouteCollectorProxy $group) {
  $group->get('[/]', function (Request $request, Response $response, $args) {
      $payload = json_encode(array("mensaje" => "Bienvenido a La Comandita"));
      sleep(2);
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  });//->add(new AuthMiddleware());
  $group->post('[/]', \LogInController::class . ':Loggearse');
});

$app->group('/clientes', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerPorCodigo');
  $group->post('[/]', \EncuestaController::class . ':CargarUno');
});

$app->group('/socio', function (RouteCollectorProxy $group) {
  $group->get('/pedido', \PedidoController::class . ':TraerPorCodigo')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->get('/pedidosVendidos', \PedidoController::class . ':TraerVendidos')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->get('/pedidoEnTiempo', \PedidoController::class . ':TraerTodosEnTiempo')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->get('/logs', \LogController::class . ':TraerTodos')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->get('/{cantComentarios}', \EncuestaController::class . ':TraerMejoresComentarios')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->get('/mesa/masUsada', \EncuestaController::class . ':TraerMesaMasUsada')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->post('/descargar', \ArchivoController::class . ':DescargaPDF')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  
});
$app->group('/mozo', function (RouteCollectorProxy $group) {
  $group->post('[/]', \PedidoController::class . ':CalcularCuenta')->add(SectorMiddleware::class .":authMozo")->add(new AuthMiddleware());
});

$app->group('/exportar', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ArchivoController::class . ':ExportarArchivo')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
});
$app->group('/importar', function (RouteCollectorProxy $group) {
  $group->post('[/]', \ArchivoController::class . ':ImportarArchivo')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
});

$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
    $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
    $group->put('[/]', \UsuarioController::class . ':ModificarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  });

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos')->add(SectorMiddleware::class .":authMozo")->add(new AuthMiddleware());
    $group->get('/{producto}', \ProductoController::class . ':TraerUno')->add(SectorMiddleware::class .":authMozo")->add(new AuthMiddleware());
    $group->post('[/]', \ProductoController::class . ':CargarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
    $group->put('[/]', \ProductoController::class . ':ModificarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
    $group->delete('[/]', \ProductoController::class . ':BorrarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->get('/{id}', \MesaController::class . ':TraerUno')->add(SectorMiddleware::class .":authMozo")->add(new AuthMiddleware());
  $group->post('[/]', \MesaController::class . ':CargarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->put('[/]', \MesaController::class . ':ModificarUno')->add(SectorMiddleware::class .":authMozo")->add(new AuthMiddleware());
  $group->delete('[/]', \MesaController::class . ':BorrarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(new AuthMiddleware());
  $group->get('/{id}', \PedidoController::class . ':TraerUno')->add(new AuthMiddleware());  // Ruta para obtener un pedido por su ID
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(SectorMiddleware::class .":authMozo")->add(new AuthMiddleware());
  $group->put('[/]', \PedidoController::class . ':ModificarUno')->add(new AuthMiddleware());
  $group->delete('[/]', \PedidoController::class . ':BorrarUno')->add(SectorMiddleware::class .":authMozo")->add(new AuthMiddleware());
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  $group->get('[/]', \EncuestaController::class . ':TraerTodos')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->get('/{pedido}', \EncuestaController::class . ':TraerUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->post('[/]', \EncuestaController::class . ':CargarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->put('[/]', \EncuestaController::class . ':ModificarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
  $group->delete('[/]', \EncuestaController::class . ':BorrarUno')->add(SectorMiddleware::class .":authSocio")->add(new AuthMiddleware());
});


// Run app
$app->run();

