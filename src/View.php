<?php

namespace Ecommerce;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathDetector;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class View {
    private string $templatePath;
    private Request $request;
    private Response $response;

    public function __construct(string $viewType, Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;

        $basePathDetector = new BasePathDetector($this->request->getServerParams());

        if ($viewType === 'admin') {
            $this->templatePath = 'Admin/';
        } else {
            $this->templatePath = 'Catalog/';
        }
    }

    public function renderView(string $template, array $data = []): Response {
        $view = Twig::fromRequest($this->request);

        try {
            return $view->render($this->response, $this->templatePath . $template, $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            print_r($e);
        }
    }
}