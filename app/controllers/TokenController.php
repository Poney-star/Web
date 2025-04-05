<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/../models/token.php");

class TokenController
{
    private $twig;
    private $tokenModel;

    public function __construct()
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->tokenModel = new TokenModel();
    }

    public function reloadpage()
    {
        $json = file_get_contents('php://input');
        $token = json_decode($json, true)['token'];
        $this->tokenModel->addToken($token['value'], $token['email'], $token['expiration']);
    }

}