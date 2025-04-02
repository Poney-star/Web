<?php

require_once '../vendor/autoload.php';
require_once '../app/controllers/HomeController';
require_once '../app/controllers/ContactController';
require_once '../app/controllers/CompaniesController';
require_once '../app/controllers/OffersController';
require_once '../app/controllers/ErrorController';
require_once '../app/controllers/UsersController';
require_once '../app/controllers/ProfileController';
require_once '../app/controllers/LoginController';
require_once '../app/controllers/SignUpController';

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
} else if ($page === 'users')
{
    $index = $_GET['i'] ?? '1';
    $controller = new UserspageController($index);
} else if ($page === 'profile')
{
    $controller = new ProfilepageController();
} else if ($page === 'login')
{
    $controller = new LoginpageController();
} else if ($page === 'sign-up')
{
    $controller = new SignUppageController();
} else {
    $controller = new ErrorpageController();
}

$controller->loadpage();