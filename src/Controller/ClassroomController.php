<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassRoomRepository ;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Classroom;
use App\Form\ClassroomType;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/classroom', name: 'app_classroom')]
    public function listclassroom(ClassRoomRepository $repository)
    {
      $classrooms=$repository->findAll();
      return $this-> render ("classroom/classroom.html.twig",array('tabclassrooms'=>$classrooms));
    }

    #[Route('/addsclassroom', name: 'add_classroom')]
    public function addClassroom(ManagerRegistry  $doctrine)
    {
        $classroom= new Classroom();
        
        $classroom->setName("3A31");
        $classroom->setNbrStudent(60);
        //$em= $this->getDoctrine()->getManager();
        $em= $doctrine->getManager();
        $em->persist($classroom);
        $em->flush();
        //return $this->redirectToRoute("")
        return new Response("add_classroom");
    }


    #[Route('/addClassroomForm', name: 'addClassroomForm')]
    public function addStudentForm(Request  $request,ManagerRegistry $doctrine)
    {
        $classroom= new  ClassRoom();
        $form= $this->createForm(ClassRoomType::class,$classroom);
        $form->handleRequest($request) ;
        if($form->isSubmitted()){
             $em= $doctrine->getManager();
             $em->persist($classroom);
             $em->flush();
             return  $this->redirectToRoute("addClassroomForm");
         }
        return $this->renderForm("classroom/add.html.twig",array("FormClassroom"=>$form));
    }






    #[Route('/updateclassroom/{id}', name: 'update_classroom')]
    public function updateclassroomForm($id,ClassRoomRepository  $repository,Request  $request,ManagerRegistry $doctrine)
    {
        $classroom= $repository->find($id);
        $form= $this->createForm(classroomType::class,$classroom);
        $form->handleRequest($request) ;
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->flush();
            return  $this->redirectToRoute("update_classroom");
        }
        return $this->renderForm("classroom/update.html.twig",array("Formclassroom"=>$form));
    }


    
    #[Route('/removeclassroom/{id}', name: 'remove_classroom')]
    public function remove(ManagerRegistry $doctrine,$id,classroomRepository $repository)
    {
        $classroom= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute("remove_classroom");
    }