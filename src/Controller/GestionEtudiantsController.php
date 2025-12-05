<?php

namespace App\Controller;

use App\Entity\Tuteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionEtudiantsController extends AbstractController{
   

     private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    
    #[Route('/etudiants', name: 'gestion_etudiants')]
    public function gestion(Request $request): Response{ 

        $repo = $this->em->getRepository(Tuteur::class);
        $session = $request->getSession();
        
        $tuteur = $repo->find($session->get('tuteur_id'));

          
        $etudiants = $tuteur->getEtudiants();
        

        $tabEtudiants = [];
        

        foreach ($etudiants as $etudiant){
            array_push($tabEtudiants, ['id'=>$etudiant->getId(),'nom'=>$etudiant->getNom(), 'prenom'=>$etudiant->getPrenom(), 'formation'=>$etudiant->getFormation()]);
        }

        return $this->render('tuteur/gestion.html.twig',['etudiants'=>$tabEtudiants]);
    }






}
