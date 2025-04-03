<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ProfileController
{
    private $twig;

    public function __construct(string $i)
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
    }

    public function loadpage()
    {
        // Rendu de la page Twig avec les données
        echo $this->twig->render('pages/profile.twig');
    }
}
