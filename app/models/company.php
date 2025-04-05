<?php
class CompanyModel {

    private $db;
    private $pdo;

    public function __construct(){
        $this->db = new DatabaseController();
        $this->pdo = $this->db->getDBConnection();
    }

    public function creerEntreprise($mail, $nom, $adresse, $description, $telephone) {
        $stmt = $pdo->prepare("INSERT INTO Entreprises VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$mail, $nom, $adresse, $description, $telephone]);
    }

    public function ajouterGerant($mailUtilisateur, $mailEntreprise) {
        $stmt = $pdo->prepare("INSERT INTO gerants VALUES (?, ?)");
        return $stmt->execute([$mailUtilisateur, $mailEntreprise]);
    }

    public function listerEntreprises() {
        $stmt = $pdo->query("SELECT * FROM Entreprises");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function entreprisesGereesParUtilisateur($mailUtilisateur) {
        $stmt = $pdo->prepare("SELECT Entreprises.* FROM Entreprises JOIN gerants ON Entreprises.Mail_Entreprise = gerants.Mail_Entreprise WHERE gere.Mail_Utilisateur = ?");
        $stmt->execute([$mailUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listCompanies($ville = null, $note = null, $page = 1, $limit = 20, $orderby = "Nom")
    {
        
        // Construction de la requête de base
        $sql = 'SELECT entreprises.Nom, entreprises.Adresse, entreprises.Ville, COUNT(offres.Id_Offre) AS Nombre_De_Stages, ROUND(AVG(evaluations.note), 1) AS Note FROM entreprises LEFT JOIN offres ON entreprises.Mail_Entreprise = offres.Mail_Entreprise LEFT JOIN evaluations ON evaluations.Mail_Entreprise = entreprises.Mail_Entreprise GROUP BY entreprises.Mail_Entreprise';
        
        $conditions = [];
        $params = [];
    
        // Gestion des villes (OR sur les LIKE)
        if (!empty($ville)) {
            if (is_array($ville)) {
                $placeholders = implode(' OR ', array_fill(0, count($ville), 'entreprises.Ville LIKE ?'));
                $conditions[] = "($placeholders)";
                foreach ($ville as $v) $params[] = "%$v%";
            } else {
                $conditions[] = 'entreprises.Ville LIKE ?';
                $params[] = "%$ville%";
            }
        }
    
        // Gestion des notes (IN pour les valeurs exactes)
        if (!empty($note)) {
            if (is_array($note)) {
                $placeholders = implode(',', array_fill(0, count($note), '?'));
                $conditions[] = "ROUND(AVG(evaluations.note), 1) IN ($placeholders)";
                $params = array_merge($params, $note);
            } else {
                $conditions[] = 'ROUND(AVG(evaluations.note), 1) = ?';
                $params[] = $note;
            }
        }
    
        // Assemblage final de la requête
        if (!empty($conditions)) {
            $sql .= ' HAVING ' . implode(' AND ', $conditions);
        }
    
        
        $sql .= " ORDER BY $orderby DESC LIMIT $limit " . ((($page - 1) * $limit) > 0 ? "OFFSET ". (($page - 1) * $limit) : '');
    
        // Exécution sécurisée
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
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