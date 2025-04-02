<?php
require_once("../app/controllers/databaseConnect");

class OfferModel {

    private $pdo;

    public function __construct(){
        $this->pdo = getDBConnection();
    }

    public function ajouterOffre($titre, $description, $remuneration, $dateDebut, $dateFin, $secteurActivite, $mailEntreprise) {
        $stmt = $pdo->prepare("INSERT INTO Offre (Titre, Description_Offre, Base_Remuneration, Date_Mise_En_Ligne, Date_Debut_Stage, Date_Fin_Stage, Secteur_Activite, Mail_Entreprise) VALUES (" . $titre . "," . $description . "," . $remuneration . "," . $dateDebut . "," . $dateFin . "NOW(), " . $secteurActivite . ", " . $mailEntreprise .  ");");
        return $stmt->execute();
    }

    public function modifierOffre($idOffre, $titre, $description, $remuneration, $dateDebut, $dateFin, $secteurActivite) {
        $stmt = $this->pdo->prepare("UPDATE Offre SET Titre = " . $titre . ", Description_Offre = " . $description . ", Base_Remuneration = " . $remuneration . ", Date_Debut_Stage = " . $dateDebut . ", Date_Fin_Stage = " . $dateFin . ", Secteur_Activite = " . $secteurActivite . " WHERE Id_Offre = " . $idOffre);
        return $stmt->execute();
    }

    public function supprimerOffre($idOffre) {
        $stmt = $this->pdo->prepare("DELETE FROM Offre WHERE Id_Offre = " . $idOffre);
        return $stmt->execute();
    }

    public function listOffers($secteur = null, $localisation = null, $duree = null, $entreprise = null, $page = 1, $limit = 20, $orderby = "Date_Mise_En_Ligne") {
        $query = 'SELECT offre.*, entreprise.Nom, entreprise.Ville, TIMESTAMPDIFF(MONTH, offre.Date_Debut_Stage, offre.Date_Fin_Stage) AS Duree FROM offre JOIN entreprise ON offre.Mail_Entreprise = entreprise.Mail_Entreprise '.($secteur ? ('AND offre.Secteur_Activite LIKE "%' . $domaine. '%" ') : '') . ($localisation ? ('AND entreprise.Ville LIKE "%' . $localisation . '%"') : '') . ($duree ? (' AND TIMESTAMPDIFF(MONTH, offre.Date_Debut_Stage, offre.Date_Fin_Stage) = ' . $duree) : '') . ($entreprise ? (' AND entreprise.Nom LIKE "%' . $entreprise . '%"') : '') . 'ORDER BY ' . $orderby . ' DESC LIMIT ' . $limit . (($page - 1) * $limit != 0 ? (' OFFSET ' . ($page - 1) * $limit) : '' ). ';';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>