<?php

require_once '../vendor/autoload.php';

require_once '../app/controllers/HomeController';
require_once '../app/controllers/ContactController';
require_once '../app/controllers/CompaniesController';
require_once '../app/controllers/OffersController';
require_once '../app/controllers/ErrorController';

$page = $_GET['page'] ?? 'home';

if ($page === 'home') 
{
    $controller = new HomepageController();
} else if ($page === 'contact')
{
    $controller = new ContactpageController();
} else if ($page === 'companies')
{
    $index = $_GET['i'] ?? '1';
    $controller = new CompaniespageController($index);
} else if ($page === 'offers')
{
    $index = $_GET['i'] ?? '1';
    $controller = new OfferspageController($index);
} else {
    $controller = new ErrorpageController();
}

$controller->loadpage();