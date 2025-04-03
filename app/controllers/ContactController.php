<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ContactController
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
        echo $this->twig->render('pages/contact.twig');
    }
}
