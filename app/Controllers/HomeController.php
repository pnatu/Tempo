<?php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // Do your stuff here
$this->view->render($response, 'profile');
        //return $response;
    }
}