<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/../models/offer.php");

class OffersController
{
    private $twig;
    private $offerIndex;
    private $offerModel;

    public function __construct(string $l)
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->offerModel = new OfferModel();
        $this->offerIndex = $l;
    }

    public function loadpage()
    {
        echo $this->twig->render('pages/offer.twig', ['offers' => $this->offerModel->listOffers(page : $this->offerIndex, limit : 20, orderby : "Date_Mise_En_Ligne"), 'page' => $this->offerIndex, 'filters' => $this->offerModel->getFilters()]);
    }

    public function reloadpage()
    {
        $json = file_get_contents('php://input');
        $filters = json_decode($json, true)['filters'];
        echo $this->twig->render('components/offers.twig', ['offers' => $this->offerModel->listOffers(secteur : $filters['sector'] ?? null, localisation : $filters['city'] ?? null, duree : $filters['duration'] ?? null, entreprise : $filters['company'] ?? null, page : 1 , limit : 20, orderby : $filters['sortOption'] ?? "Date_Mise_En_Ligne")]);
    }

    public function addToWishlist()
    {
        $this->offerModel->addToWishlist($_POST['idOffre'], $_POST['mailEtudiant']);
    }
}

?>