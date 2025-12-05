<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Tuteur;
use App\Entity\Visite;
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




      #[Route('/updateStudentForm/{id}', name: 'updateStudentForm')]
    public function updateStudentForm(Request $request, $id): Response{ 
      
      $repository = $this->em->getRepository(Etudiant::class);

      $etudiant = $repository->find($id);

      $nom = $etudiant->getNom();
      $prenom = $etudiant->getPrenom();
      $formation = $etudiant->getFormation();

      return $this->render('tuteur/updateStudentForm.html.twig', ['id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'formation'=>$formation]);
    }


      #[Route('/etudiant/update/{id}', name: 'updateStudent')]
    public function updateStudent(Request $request, $id): Response{ 

      $repository = $this->em->getRepository(Etudiant::class);
      $etudiant = $repository->find($id);
      $message="";


      $tuteur_id = $request->getSession()->get('tuteur_id');

      $repository = $this->em->getRepository(Tuteur::class);
        
      $tuteur = $repository->find($tuteur_id);

      $tuteur_nom = $tuteur->getNom();

      if(isset($_POST['submit'])){

        $nouveau_nom = $_POST['nom'];
        $nouveau_prenom = $_POST['prenom'];
        $nouvelle_formation = $_POST['formation'];

        $etudiant->setNom($nouveau_nom);
        $etudiant->setPrenom($nouveau_prenom);
        $etudiant->setFormation($nouvelle_formation);

        try{
          $this->em->flush();
          $message = "Modification de l'étudiant faite avec succes";
        }
        catch(Exception $e){
          $message="Erreur lors de la modification de l'étudiant : " . $e->getMessage();
        }
      }

      

      $etudiants = $tuteur->getEtudiants();
        

      $tabEtudiants = [];
        

      foreach ($etudiants as $etudiant){
          array_push($tabEtudiants, ['id'=>$etudiant->getId(),'nom'=>$etudiant->getNom(), 'prenom'=>$etudiant->getPrenom(), 'formation'=>$etudiant->getFormation()]);
      }


      return $this->render('tuteur/gestion.html.twig',['message'=>$message, 'tuteur_nom'=>$tuteur_nom, 'etudiants'=>$tabEtudiants]); 
      
      
    }


    

          #[Route('/showAllVisites/{id}', name: 'showAllVisites')]
    public function showAllVisites(Request $request, $id): Response{ 
      

      $repository = $this->em->getRepository(Etudiant::class);

      $etudiant = $repository->find($id);


      $visites = $etudiant->getVisites();

      $tabVisites = [];

      foreach($visites as $visite){
        array_push($tabVisites, ['id'=>$visite->getId(),'date'=>$visite->getDate(),'commantaire'=>$visite->getCommentaire(),'compteRendu'=>$visite->getCompteRendu(),'statut'=>$visite->getStatut()]);
      }


      return $this->render('tuteur/showAllVisites.html.twig', ['etudiant_id'=>$etudiant->getId(),'etudiant_nom'=>$etudiant->getNom(), 'etudiant_prenom'=>$etudiant->getPrenom(), 'etudiant_formation'=>$etudiant->getFormation(), 'visites'=>$tabVisites]);
    }





      #[Route('/addVisiteForm/{id}', name: 'addVisiteForm')]
    public function addVisiteForm(Request $request, $id): Response{ 
    
      return $this->render('tuteur/addVisiteForm.html.twig', ['id'=>$id]);
    }


    #[Route('/addVisite/{id}', name: 'addVisite')]
    public function addVisite(Request $request, $id): Response{ 

      $repository1 = $this->em->getRepository(Tuteur::class);
      $tuteur = $repository1->find($request->getSession()->get('tuteur_id'));

      $repository2 = $this->em->getRepository(Etudiant::class);
      $etudiant = $repository2->find($id);

      $message="";

      if(isset($_POST['submit']) && isset($_POST['statut'])){
        $dateString = $_POST['date'];
        $commentaire = $_POST['commentaire'];
        $statut = $_POST['statut'];
        


        $date = new \DateTimeImmutable($dateString);

        $visite = new Visite();
        $visite->setDate($date);
        $visite->setCommentaire($commentaire);
        
        $visite->setStatut($statut);
        $visite->setEtudiant($etudiant);
        $visite->setTuteur($tuteur);

        try{
          $this->em->persist($visite);
          $this->em->flush();
          $message= "Visite créer avec succes poour l'étudiant : " .$etudiant->getNom() ." " . $etudiant->getPrenom(); 
        }

        catch(Exception $e){
          $message = "Erreur lors de la création de la visite pour l'étudiant ".$etudiant->getNom() ." " . $etudiant->getPrenom() ." : " . $e->getMessage();
        }
      }

      $repository = $this->em->getRepository(Etudiant::class);

      $etudiant = $repository->find($id);


      $visites = $etudiant->getVisites();

      $tabVisites = [];

      foreach($visites as $visite){
        array_push($tabVisites, ['id'=>$visite->getId(),'date'=>$visite->getDate(),'commantaire'=>$visite->getCommentaire(),'compteRendu'=>$visite->getCompteRendu(),'statut'=>$visite->getStatut()]);
      }

      return $this->render('tuteur/showAllVisites.html.twig', ['etudiant_id'=>$etudiant->getId(),'etudiant_nom'=>$etudiant->getNom(), 'etudiant_prenom'=>$etudiant->getPrenom(), 'etudiant_formation'=>$etudiant->getFormation(), 'visites'=>$tabVisites, 'message'=>$message]);

    }







      #[Route('/editVisiteForm/{id_etudiant}/{id_visite}', name: 'editVisiteForm')]
    public function editVisiteForm(Request $request, $id_visite, $id_etudiant ): Response{ 

      $repository = $this->em->getRepository(Visite::class);

      $visite = $repository->find($id_visite);

      $visite_commentaire = $visite->getCommentaire();
    
      return $this->render('tuteur/updateVisiteForm.html.twig', ['id_visite'=>$id_visite, 'id_etudiant'=>$id_etudiant, 'commentaire'=>$visite_commentaire ]);
    }



      #[Route('/edit/visite/{id_etudiant}/{id_visite}', name: 'editVisite')]
    public function editVisite(Request $request, $id_etudiant, $id_visite): Response{ 

      $message="";

      if(isset($_POST['submit'])){
        if(isset($_POST['statut'])){

          $new_statut = $_POST['statut'];
          
          $new_dateString = $_POST['date'];

          $new_commentaire = $_POST['commentaire'];

          $repository = $this->em->getRepository(Visite::class);

          $visite = $repository->find($id_visite);

          $visite->setDate(new \DateTimeImmutable($new_dateString));
          $visite->setCommentaire($new_commentaire);
          $visite->setStatut($new_statut);

          try{
            $this->em->flush();
            $message = "Visite modifiée avec succes";
          }

          catch(Exception $e){
            $message = "Erreur lors de la modification de la visite";
          }

          

        }

      

      }
    
      $repository = $this->em->getRepository(Etudiant::class);

      $etudiant = $repository->find($id_etudiant);


      $visites = $etudiant->getVisites();

      $tabVisites = [];

      foreach($visites as $visite){
        array_push($tabVisites, ['id'=>$visite->getId(),'date'=>$visite->getDate(),'commantaire'=>$visite->getCommentaire(),'compteRendu'=>$visite->getCompteRendu(),'statut'=>$visite->getStatut()]);
      }

      return $this->render('tuteur/showAllVisites.html.twig', ['etudiant_id'=>$etudiant->getId(),'etudiant_nom'=>$etudiant->getNom(), 'etudiant_prenom'=>$etudiant->getPrenom(), 'etudiant_formation'=>$etudiant->getFormation(), 'visites'=>$tabVisites, 'message'=>$message]);

      
    }






     #[Route('compteRenduForm/{id_etudiant}/{id_visite}', name: 'visiteCompteRenduForm')]
    public function compteRenduVisite(Request $request, $id_etudiant, $id_visite): Response{ 

      $repository = $this->em->getRepository(Visite::class);

      $visite = $repository->find($id_visite);

      $ancien_compte_rendu = $visite->getCompteRendu();
      
     return $this->render('tuteur/compteRenduForm.html.twig',['etudiant_id'=>$id_etudiant, 'visite_id'=>$id_visite, 'ancien_compte_rendu'=>$ancien_compte_rendu]);
    }











}
