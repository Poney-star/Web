<?php
class UserModel {

    private $db;
    private $pdo;

    public function __construct(){
        $this->db = new DatabaseController();
        $this->pdo = $this->db->getDBConnection();
    }

    public function createUser($mail, $password, $nom, $prenom, $promo) {
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateurs (Mail_Utilisateur, Mot_De_Passe, Nom_Utilisateur, Prenom_Utilisateur, Promotion, Droits_Utilisateur) VALUES (?, ?, ?, ?, ?, ?)");
        echo $stmt->execute([$mail, password_hash($password, PASSWORD_DEFAULT), $nom, $prenom, $promo, 2]) ? "true" : "false";
    }

    public function modifierUtilisateur($mail, $nom, $prenom, $droits) {
        $stmt = $pdo->prepare("UPDATE Utilisateurs SET Nom_Utilisateur=?, Prenom_Utilisateur=?, Droits_Utilisateur=? WHERE Mail_Utilisateur=?");
        return $stmt->execute([$nom, $prenom, $droits, $mail]);
    }

    public function obtenirUtilisateur($mail) {
        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE Mail_Utilisateur=?");
        $stmt->execute([$mail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function postulerOffre($mailUtilisateur, $idOffre, $cv, $lettre) {
        $stmt = $pdo->prepare("INSERT INTO postule VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$mailUtilisateur, $idOffre, $cv, $lettre]);
    }

    public function listerOffresPostulees($mailUtilisateur) {
        $stmt = $pdo->prepare("SELECT Offre.* FROM Offre JOIN postule ON Offre.Id_Offre = postule.Id_Offre WHERE postule.Mail_Utilisateur = ?");
        $stmt->execute([$mailUtilisateur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listUsers($pages, $limit)
    {
        $stmt = $this->pdo->query("SELECT utilisateurs.Nom_Utilisateur, utilisateurs.Prenom_Utilisateur, utilisateurs.Mail_Utilisateur, utilisateurs.Droits_Utilisateur, utilisateurs.Promotion FROM utilisateurs LIMIT ".$limit.($pages > 1 ? (" OFFSET ".(($pages - 1) * $limit )) : ''));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function connectUser($email, $password)
    {
        $sql = "SELECT COUNT(*) FROM utilisateurs WHERE Mail_Utilisateur = :email AND Mot_De_Passe = :password";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password
        ]);
        echo ($stmt->fetchAll(PDO::FETCH_ASSOC) > 0) ? 'true' : 'false'; // A TRANSFORMER EN TEXTE ET CA FONCTIONNE
    }
}
?>