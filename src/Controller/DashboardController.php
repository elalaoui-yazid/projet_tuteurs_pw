<?php

namespace App\Controller;

use App\Entity\Tuteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }





#[Route('/dashboard', name: 'app_dashboard')]
public function dashboard(Request $request): Response  
{
    $repo = $this->em->getRepository(Tuteur::class);
    $session = $request->getSession();
    
    $tuteur = $repo->find($session->get('tuteur_id'));

    $nom = $tuteur->getNom();
    $prenom = $tuteur->getPrenom();
    $email = $tuteur->getEmail();
    $telephone = $tuteur->getTelephone();
    $etudiants = $tuteur->getEtudiants();
    $visites = $tuteur->getVisites();

    $tabEtudiants = [];
    $tabVisites = [];

    foreach ($etudiants as $etudiant){
        array_push($tabEtudiants, ['nom'=>$etudiant->getNom(), 'prenom'=>$etudiant->getPrenom(), 'formation'=>$etudiant->getFormation()]);
    }

    foreach($visites as $visite){
        
        array_push($tabVisites, ['etudiant'=>$visite->getEtudiant()->getNom()." ". $visite->getEtudiant()->getPrenom(), 'date'=>$visite->getDate(), 'statut'=>$visite->getStatut()]);
    }

    return $this->render('tuteur/dashboard.html.twig', [
        'tuteur_nom' => $nom,
        'tuteur_prenom' => $prenom,
        'tuteur_mail' => $email,
        'tuteur_telephone' => $telephone,
        'etudiants' => $tabEtudiants,
        'visites' => $tabVisites,
    ]);
}

}
