<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Classroom;
use App\Form\ClassroomType;
use Symfony\Component\HttpFoundation\Request;

class ClassroomController extends AbstractController
{
    #[Route('/listeclassroom', name: 'app_classroom')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Classroom::class);
        $classrooms = $repo->findAll();
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
            'classrooms'=>$classrooms
        ]);
    }
    #[Route('/deleteclassroom/{id}', name: 'delete_classroom')]
    public function deleteStudent($id,ManagerRegistry $doctrine){
        $classroom=$doctrine->getRepository(Classroom::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute('app_classroom');
    }
    #[Route('/addClassroom', name: 'add_classroom')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($classroom);
            $entityManager->flush();
            return $this->redirectToRoute('app_classroom');
        }
        return $this->render('classroom/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/modifClassroom/{id}', name: 'modif_classroom')]
    public function modif($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $classroom =  $doctrine->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
           return $this->redirectToRoute('app_classroom');
        }
        return $this->render('classroom/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
