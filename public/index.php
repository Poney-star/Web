<?php

require_once __DIR__ .'/../vendor/autoload.php';
require_once __DIR__ .'/../app/controllers/HomeController.php';
require_once __DIR__ .'/../app/controllers/ContactController.php';
require_once __DIR__ .'/../app/controllers/CompaniesController.php';
require_once __DIR__ .'/../app/controllers/OffersController.php';
require_once __DIR__ .'/../app/controllers/ErrorController.php';
require_once __DIR__ .'/../app/controllers/UsersController.php';
require_once __DIR__ .'/../app/controllers/ProfileController.php';
require_once __DIR__ .'/../app/controllers/LoginController.php';
require_once __DIR__ .'/../app/controllers/SignUpController.php';

$page = $_GET['page'] ?? 'home';

if ($page === 'home') 
{
    $controller = new HomeController();
} else if ($page === 'contact')
{
    $controller = new ContactController();
} else if ($page === 'companies')
{
    $page = $_GET['l'] ?? '1';
    $controller = new CompaniesController($page);
} else if ($page === 'offers')
{
    $page = $_GET['l'] ?? '1';
    $controller = new OffersController($page);
} else if ($page === 'users')
{
    $page = $_GET['l'] ?? '1';
    $controller = new UsersController($page);
} else if ($page === 'profile')
{
    $controller = new ProfileController();
} else if ($page === 'login')
{
    $controller = new LoginController();
} else if ($page === 'sign-up')
{
    $controller = new SignUpController();
} else {
    $controller = new ErrorController();
}

$controller->loadpage();