<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ErrorController
{
    private $twig;
    private $offerModel;

    public function __construct()
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
    }

    public function loadpage()
    {
        // Rendu de la page Twig avec les données
        echo $this->twig->render('errors/errornotfound.twig');
    }
}