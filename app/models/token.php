<?php
include_once(__DIR__ ."/../controllers/DatabaseController.php");

class TokenModel {
    private $db;
    private $pdo;

    public function __construct()
    {
        $this->db = new DatabaseController();
        $this->pdo = $this->db->getDBConnection();
    }

    public function addToken($token, $Mail_Utilisateur, $Date_Expiration){
        try {
            $query = "INSERT INTO Tokens (Valeur_Token, Mail_Utilisateur, Date_Expiration) 
                      VALUES (:token, :mail_utilisateur, :date_expiration)";
            
            $stmt = $this->pdo->prepare($query);
            
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':mail_utilisateur', $Mail_Utilisateur, PDO::PARAM_STR);
            $stmt->bindParam(':date_expiration', $Date_Expiration, PDO::PARAM_STR);
            
            $result = $stmt->execute();
            
            return $result ? "true" : "false";
        } catch (PDOException $e) {
            // Log l'erreur ou gère-la selon tes besoins
            error_log("Erreur lors de l'ajout du token: " . $e->getMessage());
            return false;
        }
    }

    public function getUser($token)
    {
        $stmt = $this->pdo->prepare("SELECT utilisateurs.Mail_Utilisateur, utilisateurs.Nom_Utilisateur, utilisateurs.Prenom_Utilisateur, utilisateurs.Droits_Utilisateur, utilisateurs.Promotion 
        FROM tokens 
        JOIN utilisateurs ON tokens.Mail_Utilisateur = utilisateurs.Mail_Utilisateur 
        WHERE tokens.Valeur_Token = :token");
        $stmt->execute([':token' => $token]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>