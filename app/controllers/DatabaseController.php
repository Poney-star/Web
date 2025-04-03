<?php
class DatabaseController {

    public function getDBConnection() {
        $host = 'localhost';
        $dbname = 'stage_co';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function closeDBConnection($pdo) {
        $pdo = null;
    }
}

?>