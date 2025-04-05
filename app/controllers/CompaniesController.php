<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
include_once(__DIR__ ."/../models/company.php");


class CompaniesController
{
    private $twig;
    private $companyIndex;
    private $companyModel;

    public function __construct($l = 1)
    {
        // Initialisation de Twig
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader);
        $this->companyModel = new CompanyModel();
        $this->companyIndex = $l;
    }

    public function loadpage()
    {
        echo $this->twig->render('pages/company.twig', ['companies' => $this->companyModel->listCompanies(page : $this->companyIndex, limit : 20, orderby : "Note") ,'page' => $this->companyIndex, 'filters' => $this->companyModel->getFilters()]);
    }

    public function reloadpage()
    {
        $json = file_get_contents('php://input');
        $filters = json_decode($json, true)['filters'];
        echo $this->twig->render('components/companies.twig', ['companies' => $this->companyModel->listCompanies(ville : $filters['city'] ?? null, note : $filters['note'] ?? null, page : 1 , limit : 20, orderby : $filters['sortOption'] ?? "Note")]);
    }
}   
