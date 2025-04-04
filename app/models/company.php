<?php
class CompanyModel {

    private $db;
    private $pdo;

    public function __construct(){
        $this->db = new DatabaseController();
        $this->pdo = $this->db->getDBConnection();
    }

    public function creerEntreprise($mail, $nom, $adresse, $description, $telephone) {
        $stmt = $pdo->prepare("INSERT INTO Entreprise VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$mail, $nom, $adresse, $description, $telephone]);
    }

    public function ajouterGerant($mailUtilisateur, $mailEntreprise) {
        $stmt = $pdo->prepare("INSERT INTO gere VALUES (?, ?)");
        return $stmt->execute([$mailUtilisateur, $mailEntreprise]);
    }

    public function listerEntreprises() {
        $stmt = $pdo->query("SELECT * FROM Entreprise");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function entreprisesGereesParUtilisateur($mailUtilisateur) {
        $stmt = $pdo->prepare("SELECT Entreprise.* FROM Entreprise JOIN gere ON EntrepriseE.Mail_Entreprise = gere.Mail_Entreprise WHERE gere.Mail_Utilisateur = ?");
        $stmt->execute([$mailUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listCompanies($ville = null, $note = null, $page = 1, $limit = 20, $orderby = "Nom")
    {
        
        // Construction de la requête de base
        $sql = 'SELECT entreprise.Nom, entreprise.Adresse, entreprise.Ville, COUNT(offre .Id_Offre) AS Nombre_De_Stages, ROUND(AVG(evaluations.note), 1) AS Note FROM entreprise LEFT JOIN offre ON entreprise.Mail_Entreprise = offre.Mail_Entreprise LEFT JOIN evaluations ON evaluations.Mail_Entreprise = entreprise.Mail_Entreprise GROUP BY entreprise.Mail_Entreprise';
        
        $conditions = [];
        $params = [];
    
        // Gestion des villes (OR sur les LIKE)
        if (!empty($ville)) {
            if (is_array($ville)) {
                $placeholders = implode(' OR ', array_fill(0, count($ville), 'entreprise.Ville LIKE ?'));
                $conditions[] = "($placeholders)";
                foreach ($ville as $v) $params[] = "%$v%";
            } else {
                $conditions[] = 'entreprise.Ville LIKE ?';
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
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
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
            "SELECT DISTINCT entreprise.Nom 
             FROM entreprise 
             JOIN offre ON offre.Mail_Entreprise = entreprise.Mail_Entreprise 
             GROUP BY entreprise.Nom 
             HAVING COUNT(offre.Id_Offre) > 0",

            "SELECT DISTINCT offre.Secteur_Activite FROM offre",

            "SELECT DISTINCT entreprise.Ville 
             FROM entreprise 
             JOIN offre ON offre.Mail_Entreprise = entreprise.Mail_Entreprise 
             GROUP BY entreprise.Ville 
             HAVING COUNT(offre.Id_Offre) > 0",

            "SELECT DISTINCT TIMESTAMPDIFF(MONTH, offre.Date_Debut_Stage, offre.Date_Fin_Stage) AS Duree FROM offre"
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