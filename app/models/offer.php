<?php
class Offer {

    private $pdo;
    private $company;
    private $city;
    private $domain;
    private $length;
    private $wage;

    public function ajouterOffre($titre, $description, $competences, $remuneration, $dateDebut, $dateFin, $mailEntreprise) {
        $stmt = $pdo->prepare("INSERT INTO Offre (Titre, Description_Offre, Competences, Base_Remuneration, Date_Mise_En_Ligne, Date_Debut_Stage, Date_Fin_Stage, Mail_Entreprise) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
        return $stmt->execute([$titre, $description, $competences, $remuneration, $dateDebut, $dateFin, $mailEntreprise]);
    }

    public function supprimerOffre($idOffre) {
        $stmt = $pdo->prepare("DELETE FROM Offre WHERE Id_Offre = ?");
        return $stmt->execute([$idOffre]);
    }

    public function listerOffres($domaine = null, $localisation = null, $duree = null, $entreprise = null, $page = 1, $limit = 20) {
        $query = "SELECT Offre.*, Entreprise.Nom AS Nom_Entreprise, Entreprise.Adresse 
                  FROM Offre 
                  JOIN Entreprise ON Offre.Mail_Entreprise = Entreprise.Mail_Entreprise 
                  WHERE 1=1";
        $params = [];
        
        if ($domaine) {
            $query .= " AND Offre.Competences LIKE ?";
            $params[] = "%$domaine%";
        }
        if ($localisation) {
            $query .= " AND Entreprise.Adresse LIKE ?";
            $params[] = "%$localisation%";
        }
        if ($duree) {
            $query .= " AND TIMESTAMPDIFF(MONTH, Offre.Date_Debut_Stage, Offre.Date_Fin_Stage) = ?";
            $params[] = $duree;
        }
        if ($entreprise) {
            $query .= " AND Entreprise.Nom LIKE ?";
            $params[] = "%$entreprise%";
        }

        // Calculate offset and add LIMIT clause
        $offset = ($page - 1) * $limit;
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>