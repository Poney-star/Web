<?php
include_once(__DIR__ ."/../controllers/DatabaseController.php");

class OfferModel {

    private $db;
    private $pdo;

    public function __construct(){
        $this->db = new DatabaseController();
        $this->pdo = $this->db->getDBConnection();
    }

    public function ajouterOffre($titre, $description, $remuneration, $dateDebut, $dateFin, $secteurActivite, $mailEntreprise) {
        $stmt = $pdo->prepare("INSERT INTO Offres (Titre, Description_Offre, Base_Remuneration, Date_Mise_En_Ligne, Date_Debut_Stage, Date_Fin_Stage, Secteur_Activite, Mail_Entreprise) VALUES (" . $titre . "," . $description . "," . $remuneration . "," . $dateDebut . "," . $dateFin . "NOW(), " . $secteurActivite . ", " . $mailEntreprise .  ");");
        return $stmt->execute();
    }

    public function modifierOffre($idOffre, $titre, $description, $remuneration, $dateDebut, $dateFin, $secteurActivite) {
        $stmt = $this->pdo->prepare("UPDATE Offres SET Titre = " . $titre . ", Description_Offre = " . $description . ", Base_Remuneration = " . $remuneration . ", Date_Debut_Stage = " . $dateDebut . ", Date_Fin_Stage = " . $dateFin . ", Secteur_Activite = " . $secteurActivite . " WHERE Id_Offre = " . $idOffre);
        return $stmt->execute();
    }

    public function supprimerOffre($idOffre) {
        $stmt = $this->pdo->prepare("DELETE FROM Offres WHERE Id_Offre = " . $idOffre);
        return $stmt->execute();
    }

    public function listOffers($secteur = null, $localisation = null, $duree = null, $entreprise = null, $page = 1, $limit = 20, $orderby = "Date_Mise_En_Ligne") {
    
        // Construction de la requête de base
        $sql = 'SELECT offres.Titre, offres.Secteur_Activite, offres.Base_Remuneration , entreprises.Nom, entreprises.Ville, 
                TIMESTAMPDIFF(MONTH, offres.Date_Debut_Stage, offres.Date_Fin_Stage) AS Duree 
                FROM offres 
                JOIN entreprises ON offres.Mail_Entreprise = entreprises.Mail_Entreprise';
        
        $conditions = [];
        $params = [];
    
        // Gestion des secteurs (OR sur les LIKE)
        if (!empty($secteur)) {
            if (is_array($secteur)) {
                $placeholders = implode(' OR ', array_fill(0, count($secteur), 'offres.Secteur_Activite LIKE ?'));
                $conditions[] = "($placeholders)";
                foreach ($secteur as $s) $params[] = "%$s%";
            } else {
                $conditions[] = 'offres.Secteur_Activite LIKE ?';
                $params[] = "%$secteur%";
            }
        }
    
        // Gestion des localisations (OR sur les LIKE)
        if (!empty($localisation)) {
            if (is_array($localisation)) {
                $placeholders = implode(' OR ', array_fill(0, count($localisation), 'entreprises.Ville LIKE ?'));
                $conditions[] = "($placeholders)";
                foreach ($localisation as $l) $params[] = "%$l%";
            } else {
                $conditions[] = 'entreprises.Ville LIKE ?';
                $params[] = "%$localisation%";
            }
        }
    
        // Gestion des durées (IN pour les valeurs exactes)
        if (!empty($duree)) {
            if (is_array($duree)) {
                $placeholders = implode(',', array_fill(0, count($duree), '?'));
                $conditions[] = "TIMESTAMPDIFF(MONTH, offres.Date_Debut_Stage, offres.Date_Fin_Stage) IN ($placeholders)";
                $params = array_merge($params, $duree);
            } else {
                $conditions[] = 'TIMESTAMPDIFF(MONTH, offres.Date_Debut_Stage, offres.Date_Fin_Stage) = ?';
                $params[] = $duree;
            }
        }
    
        // Gestion des entreprises (OR sur les LIKE)
        if (!empty($entreprise)) {
            if (is_array($entreprise)) {
                $placeholders = implode(' OR ', array_fill(0, count($entreprise), 'entreprises.Nom LIKE ?'));
                $conditions[] = "($placeholders)";
                foreach ($entreprise as $e) $params[] = "%$e%";
            } else {
                $conditions[] = 'entreprises.Nom LIKE ?';
                $params[] = "%$entreprise%";
            }
        }
    
        // Assemblage final de la requête
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
    
        // Validation du tri pour prévenir les injections
        $allowedOrders = ['Date_Mise_En_Ligne', 'Nom', 'Ville', 'Duree'];
        $orderby = in_array($orderby, $allowedOrders) ? $orderby : 'Date_Mise_En_Ligne';
        $sql .= " ORDER BY $orderby DESC LIMIT 20";
    
        // Exécution sécurisée
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchOfferbyKeyword($keyword)
    {
        $query = "SELECT entreprises.Nom, offres.Titre, entreprises.Ville, offres.Secteur_Activite, offres.Base_Remuneration, TIMESTAMPDIFF(MONTH, offres.Date_Debut_Stage, offres.Date_Fin_Stage) AS Duree FROM offre JOIN entreprises ON offres.Mail_Entreprise = entreprises.Mail_Entreprise WHERE offres.Titre LIKE " . "%$keyword%";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchOfferbyCity($city)
    {
        $query = "SELECT entreprises.Nom, offres.Titre, entreprises.Ville, offres.Secteur_Activite, offres.Base_Remuneration, TIMESTAMPDIFF(MONTH, offres.Date_Debut_Stage, offres.Date_Fin_Stage) AS Duree FROM offres JOIN entreprises ON offres.Mail_Entreprise = entreprises.Mail_Entreprise WHERE entreprises.Ville LIKE " . "%$city%";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilters()
    {
        $queries = [
            "SELECT DISTINCT entreprises.Nom 
             FROM entreprises
             JOIN offres ON offres.Mail_Entreprise = entreprises.Mail_Entreprise 
             GROUP BY entreprises.Nom 
             HAVING COUNT(offres.Id_Offre) > 0",

            "SELECT DISTINCT offres.Secteur_Activite FROM offres",

            "SELECT DISTINCT entreprises.Ville 
             FROM entreprises
             JOIN offres ON offres.Mail_Entreprise = entreprises.Mail_Entreprise 
             GROUP BY entreprises.Ville 
             HAVING COUNT(offres.Id_Offre) > 0",

            "SELECT DISTINCT TIMESTAMPDIFF(MONTH, offres.Date_Debut_Stage, offres.Date_Fin_Stage) AS Duree FROM offres"
        ];
        $filters = [];
        foreach ($queries as $query) {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $filters =  array_merge($filters, $stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        return $filters;
    }
}
?>