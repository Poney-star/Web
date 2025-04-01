<?php
class Company {

    private $pdo;

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
}
?>