<?php
class Apply {

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