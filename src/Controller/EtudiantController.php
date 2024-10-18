<?php

namespace App\Controller;
use App\Form\EtudiantType;
use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class EtudiantController extends AbstractController
{
    #[Route('/afficher',name:'app_afficher')]
    public function afficher(EtudiantRepository $repoEtud): Response
    {
     $list=$repoEtud->findAll();
     return $this->render('etudiant/read.html.twig',['etuds'=>$list]);
     }
     #[Route('/add',name:'Etudiant_add')]
     public function Add(ManagerRegistry $doctrine,Request $request): Response
     {
       $etudiant=new Etudiant();
       $form=$this->createForm(EtudiantType::class,$etudiant);
       $form->handleRequest($request);
       if ($form->isSubmitted()){
       $em=$doctrine->getManager();
       $em->persist($etudiant);
       $em->flush();
       return $this->redirectToRoute('app_afficher');
         }
         return $this->render('etudiant/add.html.twig',['form'=>$form->createView()]);
     }
     #[Route('/update/{id}',name:'etudiant_update')]
     public function Update(ManagerRegistry $doctrine,Request $request,$id,EtudiantRepository $repoEtud): Response
     {
        $etud=$repoEtud->find($id);
        $form=$this->createForm(EtudiantType::class,$etud);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
        $em=$doctrine->getManager();
        $em->persist($etud);
        $em->flush();
        return $this->redirectToRoute('app_afficher');
          }
          return $this->render('etudiant/update.html.twig',['form'=>$form->createView()]);
     }  
     #[Route('/delete/{id}',name:'etudiant_delete')]
     public function delete($id,EtudiantRepository $repoEtud,ManagerRegistry $doctrine): Response
     {
       $etudiant=$repoEtud->find($id);
       $em=$doctrine->getManager();
       $em->remove($etudiant);
       $em->flush();
       return $this->redirectToRoute('app_afficher');
     }
}
