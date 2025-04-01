<?php
class Notes {
    
    private $pdo;

    public function ajouterNote($mailUtilisateur, $mailEntreprise, $note) {
        $stmt = $pdo->prepare("INSERT INTO évalue VALUES (?, ?, ?)");
        return $stmt->execute([$mailUtilisateur, $mailEntreprise, $note]);
    }

    public function modifierNote($mailUtilisateur, $mailEntreprise, $note) {
        $stmt = $pdo->prepare("UPDATE évalue SET note = ? WHERE Mail_Utilisateur = ? AND Mail_Entreprise = ?");
        return $stmt->execute([$note, $mailUtilisateur, $mailEntreprise]);
    }

    public function supprimerNote($mailUtilisateur, $mailEntreprise) {
        $stmt = $pdo->prepare("DELETE FROM évalue WHERE Mail_Utilisateur = ? AND Mail_Entreprise = ?");
        return $stmt->execute([$mailUtilisateur, $mailEntreprise]);
    }
}
?>