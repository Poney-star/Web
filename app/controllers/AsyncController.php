<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/OffersController.php");

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $controller = new OffersController(1);
    $controller->reloadpage();
    exit;
}

header("HTTP/1.0 403 Forbidden");
echo "Accès interdit";
?>