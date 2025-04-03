<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class CompaniesController
{
    private $twig;
    private $companyIndex;
    private $companies;

    public function __construct($i)
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->$companies = new CompanyModel();
        $this->$companyIndex = $i;
    }

    public function loadpage()
    {
        // Rendu de la page Twig avec les données
        echo $this->twig->render('pages/companies.twig');
    }

    public function getCompanies()
    {

    }
}   
