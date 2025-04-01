<?php
class User {

    private $pdo;
    private $name;
    private $prename;
    private $birthdate;
    private $level;
    private $mail;
    private $phone;
    private $privilege;

    public function enregistrerUtilisateur($mail, $password, $nom, $prenom, $droits) {
        $stmt = $pdo->prepare("INSERT INTO Utilisateur VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$mail, password_hash($password, PASSWORD_DEFAULT), $nom, $prenom, $droits]);
    }

    public function modifierUtilisateur($mail, $nom, $prenom, $droits) {
        $stmt = $pdo->prepare("UPDATE Utilisateur SET Nom_Utilisateur=?, Prenom_Utilisateur=?, Droits_Utilisateur=? WHERE Mail_Utilisateur=?");
        return $stmt->execute([$nom, $prenom, $droits, $mail]);
    }

    public function obtenirUtilisateur($mail) {
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE Mail_Utilisateur=?");
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>