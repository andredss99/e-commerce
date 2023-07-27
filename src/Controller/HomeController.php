<?php

namespace Ecommerce\Controller;

use Ecommerce\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return (new View(View::VIEW_TYPE_CATALOG, $request, $response))->renderView('index.twig');
    }
}
