<?php

namespace App\Controller;


use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/listestudents', name: 'app_student')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Student::class);
        $students = $repo->findAll();
        return $this->render('student/index.html.twig', [
            'controller_name' => 'ClassroomController',
            'students'=>$students
        ]);
    }
    #[Route('/addStudent', name: 'add_student')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($student);
            $entityManager->flush();
            return $this->redirectToRoute('app_student');
        }
        return $this->render('student/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/deletestudent/{id}', name: 'delete_student')]
    public function deleteStudent($id,ManagerRegistry $doctrine){
        $student=$doctrine->getRepository(Student::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($student);
        $em->flush();
        return $this->redirectToRoute('app_student');
    }
    #[Route('/modifstudent/{id}', name: 'modif_student')]
    public function modif($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $student =  $doctrine->getRepository(Student::class)->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_student');
        }
        return $this->render('student/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
