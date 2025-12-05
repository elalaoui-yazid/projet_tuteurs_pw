<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }



     #[Route('/loginPage', name: 'login_page')]
    public function loginAcces(): Response
    {
        return $this->render('login/login.html.twig');
    }
}
