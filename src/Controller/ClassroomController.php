<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassroomRepository;
use App\Form\ClassroomType;
use App\Entity\Classroom;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SubmitType;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }


    #[Route('/list', name: 'list_class')]
    public function listClassroom(ClassroomRepository $repository){
        $classroom=$repository->findAll();
        return $this->render("classroom/list.html.twig",
        array('list_class'=>$classroom));
    }

    #[Route('/addClass', name: 'addClass')]
    public function addClassroom(Request  $request,ManagerRegistry $doctrine)
    {
        $classroom= new  Classroom();
        $form= $this->createForm(ClassroomType::class,$classroom);
        $form->handleRequest($request) ;
        if($form->isSubmitted()){
             $em= $doctrine->getManager();
             $em->persist($classroom);
             $em->flush();
             return  $this->redirectToRoute("addClass");
         }
        return $this->renderForm("classroom/add.html.twig",array("Form_class"=>$form));
    }

    #[Route('/update/{id}', name: 'update_class')]
    public function updateClassroom($id,ClassroomRepository  $repository,Request  $request,ManagerRegistry $doctrine)
    {
        $classroom= $repository->find($id);
        $form= $this->createForm(ClassroomType::class,$classroom);
        $form->handleRequest($request) ;
        if($form->isSubmitted()){
            $em= $doctrine->getManager();
            $em->flush();
            return  $this->redirectToRoute("addClass");
        }
        return $this->renderForm("classroom/update.html.twig",array("Form_class"=>$form));
    }

    #[Route('/remove/{id}', name: 'remove_class')]
    public function remove(ManagerRegistry $doctrine,$id,ClassroomRepository $repository)
    {
        $classroom= $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute("addClass");
    }
}
