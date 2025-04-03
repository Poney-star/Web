<?php


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
require_once(__DIR__ ."/OffersController.php");

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $controller = new OffersController(1); // 1 pour la page par défaut
    $controller->reloadpage();
    exit;
}

header("HTTP/1.0 403 Forbidden");
echo "Accès interdit";