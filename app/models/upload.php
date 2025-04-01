<?php
// Dossier où seront stockés les fichiers
$uploadDir = "uploads/";

// Vérifier si le dossier existe, sinon le créer
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["fichier"]) && $_FILES["fichier"]["error"] == 0) {
        $fileName = basename($_FILES["fichier"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        
        // Types de fichiers autorisés
        $allowedTypes = ["jpg", "jpeg", "png", "pdf", "txt"];
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $targetFilePath)) {
                echo "Le fichier " . htmlspecialchars($fileName) . " a été téléchargé avec succès.";
            } else {
                echo "Erreur lors du téléchargement.";
            }
        } else {
            echo "Type de fichier non autorisé.";
        }
    } else {
        echo "Aucun fichier envoyé ou erreur lors du téléchargement.";
    }
}

?>