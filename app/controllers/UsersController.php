<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/../models/user.php");

class UsersController
{
    private $twig;
    private $userModel;
    private $userIndex;

    public function __construct($l)
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->userModel = new UserModel();
        $this->userIndex = $l;
    }

    public function loadpage()
    {
        // Rendu de la page Twig avec les données
        echo $this->twig->render('pages/user.twig', ['users' => $this->userModel->listUsers($this->userIndex, 20)]);
    }
}
