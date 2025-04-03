<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class SignUpController
{
    private $twig;

    public function __construct()
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
    }

    public function loadpage()
    {
        // Rendu de la page Twig avec les données
        echo $this->twig->render('pages/signup.twig');
    }
}
