<?php

require_once '../vendor/autoload.php';

require_once '../app/controllers/HomepageController';

// Simulation d'une route
$page = $_GET['page'] ?? 'homepage';

if ($page === 'homepage') {
    $controller = new HomepageController();
    $controller->loadHomepage();
} else {
    echo "Page non trouvée";
}
