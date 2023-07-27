<?php

use Ecommerce\View;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$twig = Twig::create('../view/', [
    'cache' => '../view/cache/',
    'debug' => true
]);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add(new BasePathMiddleware($app));
$app->add(TwigMiddleware::create($app, $twig));

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', [\Ecommerce\Controller\HomeController::class, 'index']);

$app->get('/admin', [\Ecommerce\Controller\AdminController::class, 'index']);

$app->get('/admin/login', function (Request $request, Response $response) {
    $template = new View(View::VIEW_TYPE_ADMIN, $request, $response);
    return $template->renderView('login.twig');
});

$app->get('/admin/logout', [\Ecommerce\Controller\AdminController::class, 'logout']);

$app->post('/admin/login', [\Ecommerce\Controller\AdminController::class, 'login']);

$app->run();