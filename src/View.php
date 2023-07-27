<?php

namespace Ecommerce;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathDetector;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class View
{
    public const VIEW_TYPE_ADMIN = 1;
    public const VIEW_TYPE_CATALOG = 2;
    private string $templatePath;
    private Request $request;
    private Response $response;

    public function __construct(int $viewType, Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        if ($viewType === self::VIEW_TYPE_ADMIN) {
            $this->templatePath = 'Admin/';
        } else {
            $this->templatePath = 'Catalog/';
        }
    }

    public function renderView(string $template, array $data = []): Response
    {
        $view = Twig::fromRequest($this->request);

        try {
            return $view->render($this->response, $this->templatePath . $template, $data);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            print_r($e);
        }
    }
}
