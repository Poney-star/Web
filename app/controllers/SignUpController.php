<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/../models/user.php");

class SignUpController
{
    private $twig;
    private $userModel;

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
        echo $this->twig->render('pages/signup.twig');
    }

    public function reloadpage()
    {
        $json = file_get_contents('php://input');
        $fields = json_decode($json, true)['fields'];
        $this->userModel->createUser($fields['email'], $fields['password'], $fields['lastname'], $fields['firstname'], $fields['promo']);
    }
}