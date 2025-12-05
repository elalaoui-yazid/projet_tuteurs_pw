<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Tuteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

        $tuteur_nom = $tuteur->getNom();
        $etudiants = $tuteur->getEtudiants();
        

        $tabEtudiants = [];
        

        foreach ($etudiants as $etudiant){
            array_push($tabEtudiants, ['id'=>$etudiant->getId(),'nom'=>$etudiant->getNom(), 'prenom'=>$etudiant->getPrenom(), 'formation'=>$etudiant->getFormation()]);
        }

        return $this->render('tuteur/gestion.html.twig',['etudiants'=>$tabEtudiants, 'tuteur_nom'=>$tuteur_nom]);
    }



       #[Route('/addStudentForm', name: 'addStudentForm')]
    public function ajouterEtudiantForm(Request $request): Response{ 

      return $this->render('tuteur/addStudentForm.html.twig');
    }


      #[Route('/etudiant/new', name: 'addStudent')]
    public function ajouterEtudiant(Request $request): Response{ 

        $tuteur_id = $request->getSession()->get('tuteur_id');

        $repository = $this->em->getRepository(Tuteur::class);
        
        $tuteur = $repository->find($tuteur_id);

        $tuteur_nom = $tuteur->getNom();

        $message = "";

      if(isset($_POST['submit'])){

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $formation = $_POST['formation'];

        $etudiant = new Etudiant();

        $etudiant->setNom($nom);
        $etudiant->setPrenom($prenom);
        $etudiant->setFormation($formation);

        $etudiant->setTuteur($tuteur);

        try{
        $this->em->persist($etudiant);

        $this->em->flush();
          
        $message = "Etudiant ajouté avec succes";

        }

        catch (Exception $e){
          $message = "Erreur lors de l'ajout de l'étudiant : " . $e->getMessage() ;
        }

      }

        $etudiants = $tuteur->getEtudiants();
        

        $tabEtudiants = [];
        

        foreach ($etudiants as $etudiant){
            array_push($tabEtudiants, ['id'=>$etudiant->getId(),'nom'=>$etudiant->getNom(), 'prenom'=>$etudiant->getPrenom(), 'formation'=>$etudiant->getFormation()]);
        }


      return $this->render('tuteur/gestion.html.twig',['message'=>$message, 'tuteur_nom'=>$tuteur_nom, 'etudiants'=>$tabEtudiants]); 
    }




      #[Route('/updateStudentForm', name: 'updateStudentForm')]
    public function updateStudentForm(Request $request): Response{ 

      return $this->render('tuteur/updateStudentForm.html.twig');
    }





}
