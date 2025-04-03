<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
require_once(__DIR__ ."/../models/offer.php");

class HomeController
{
    private $twig;
    private $offerModel;

    public function __construct()
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->offerModel = new OfferModel();
    }

    public function loadpage()
    {
        // Rendu de la page Twig avec les données
        echo $this->twig->render('pages/homepage.twig', ['offers' => $this->offerModel->listOffers(page : 1, limit : 3, orderby : "Date_Mise_En_Ligne")]);
    }
}
