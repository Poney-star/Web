<?php
require_once("../app/controllers/databaseConnect");

class OfferModel {

    private $pdo;
    private $company;
    private $city;
    private $domain;
    private $length;
    private $wage;

    public function __construct(){
        $this->pdo = getDBConnection();
    }

    public function ajouterOffre($titre, $description, $competences, $remuneration, $dateDebut, $dateFin, $mailEntreprise) {
        $stmt = $pdo->prepare("INSERT INTO Offre (Titre, Description_Offre, Competences, Base_Remuneration, Date_Mise_En_Ligne, Date_Debut_Stage, Date_Fin_Stage, Mail_Entreprise) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
        return $stmt->execute([$titre, $description, $competences, $remuneration, $dateDebut, $dateFin, $mailEntreprise]);
    }

    public function supprimerOffre($idOffre) {
        $stmt = $pdo->prepare("DELETE FROM Offre WHERE Id_Offre = ?");
        return $stmt->execute([$idOffre]);
    }

    public function listOffers($domaine = null, $localisation = null, $duree = null, $entreprise = null, $page = 1, $limit = 20, $orderby = "Date_Mise_En_Ligne") {
        $query = 'SELECT offre.*, entreprise.Nom, entreprise.Ville, TIMESTAMPDIFF(MONTH, offre.Date_Debut_Stage, offre.Date_Fin_Stage) AS Duree FROM offre JOIN entreprise ON offre.Mail_Entreprise = entreprise.Mail_Entreprise '.($domaine ? ('AND offre.Secteur_Activite LIKE "%' . $domaine. '%" ') : '') . ($localisation ? ('AND entreprise.Ville LIKE "%' . $localisation . '%"') : '') . ($duree ? (' AND TIMESTAMPDIFF(MONTH, offre.Date_Debut_Stage, offre.Date_Fin_Stage) = ' . $duree) : '') . ($entreprise ? (' AND entreprise.Nom LIKE "%' . $entreprise . '%"') : '') . 'ORDER BY ' . $orderby . ' DESC LIMIT ' . $limit . (($page - 1) * $limit != 0 ? (' OFFSET ' . ($page - 1) * $limit) : '' ). ';';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>