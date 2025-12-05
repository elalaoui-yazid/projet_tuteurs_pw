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



    #[Route('/login', name: 'login' ,methods:['POST'])]
    public function login(Request $request): Response
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



     #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();

        if($session->has('tuteur_id')){
            $session->remove('tuteur_id');

            $message = "Déconnexion faite avec succes";

            return $this->render('login/login.html.twig',['message'=>$message]);

        }

        $message = "Erreur servenue lors de la Déconnexion";
        return $this->render('tuteur/dashboard.html.twig',['message'=>$message]); 
    }
}