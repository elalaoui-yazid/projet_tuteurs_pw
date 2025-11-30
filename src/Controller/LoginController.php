<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Tuteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

//machi blastou

 #[Route('/loginPage', name: 'login')]
    public function loginAcces(): Response
    {
    
        return $this->render('tuteur/login.html.twig');
    }



    #[Route('/login', name: 'app_login' ,methods:['POST'])]
    public function index(Request $request): Response
    {
        
        $emailUser=$request->request->get('email');

        $repository = $this->em->getRepository(Tuteur::class);

        $user = $repository->findOneBy(['email'=>$emailUser]);

        if(isset($user)){
            $session = $request->getSession();
            $session->set('tuteur_id', $user->getId());
            return $this->render('tuteur/dashboard.html.twig', ['userName'=>$user->getNom()]);
        }

        $error = "Erreur de connexion, Email inconnu";
        return $this->render('loginFail.html.twig',['erreur'=>$error]);
    }
}
