<?php

namespace Ecommerce\Controller;

use Ecommerce\Domain\Model\User;
use Ecommerce\Domain\Repository\UserRepository;
use Ecommerce\Infrastructure\DB\DbConnection;
use Ecommerce\View;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Slim\Psr7\Response;

class AdminController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION]->admin) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        return (new View(View::VIEW_TYPE_ADMIN, $request, $response->withStatus(302)))->renderView('index.twig');
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $dbConnection = (new DbConnection())->getConnection();

        $repository = new UserRepository($dbConnection);
        $requestParameters = $request->getParsedBody();

        $login = filter_var($requestParameters['login'], FILTER_UNSAFE_RAW);
        $password = filter_var($requestParameters['password'], FILTER_UNSAFE_RAW);

        $user = $repository->getOneBy('deslogin', $login);

        if (!$user || !password_verify($password, $user->getPassword())) {
            $response->withStatus(401);
            $view = new View(View::VIEW_TYPE_ADMIN, $request, $response);
            return $view->renderView('login.twig', ['login_error' => true]);
        }

        $_SESSION[User::SESSION] = json_decode($user->getData());

        return $response
            ->withStatus(302)
            ->withHeader('Location', '/admin');
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        session_unset();

        return (new View(View::VIEW_TYPE_ADMIN, $request, $response))->renderView('login.twig');
    }
}
