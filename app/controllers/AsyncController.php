<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/OffersController.php");
include_once(__DIR__ ."/CompaniesController.php");

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    switch($_POST['action']){
        case 'offer':
            $controller = new OffersController(1);
            break;
        case 'company':
            $controller = new CompaniesController(1);
            break;
        default:
            header("HTTP/1.0 400 Bad Request");
            echo "Action non reconnue";
            exit;
    }
    $controller->reloadpage();
    exit;
}

header("HTTP/1.0 403 Forbidden");
echo "Accès interdit";
?>