<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Student;
use App\Form\ClubType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{

    #[Route('/listeclubs', name: 'app_club')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Club::class);
        $clubs = $repo->findAll();
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
            'clubs'=>$clubs
        ]);
    }
    #[Route('/deleteclub/{id}', name: 'delete_club')]
    public function deleteClub($id,ManagerRegistry $doctrine){
        $club=$doctrine->getRepository(Club::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($club);
        $em->flush();
        return $this->redirectToRoute('app_club');
    }
    #[Route('/addClub', name: 'add_club')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $club = new Club();

        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            
            $entityManager->persist($club);
            $entityManager->flush();
            return $this->redirectToRoute('app_club');
        }
        return $this->render('club/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/modifclub/{id}', name: 'modif_club')]
    public function modif($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $club =  $doctrine->getRepository(Club::class)->find($id);
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_club');
        }
        return $this->render('club/modifer.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
