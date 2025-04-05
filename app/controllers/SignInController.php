<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/../models/user.php");

class SignInController
{
    private $twig;

    public function __construct()
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->userModel = new UserModel();
    }

    public function loadpage()
    {
        // Rendu de la page Twig avec les données
        echo $this->twig->render('pages/signin.twig');
    }

    public function reloadpage()
    {
        $json = file_get_contents('php://input');
        $fields = json_decode($json, true)['fields'];
        $this->userModel->connectUser($fields['email'], $fields['password']);
    }
}
