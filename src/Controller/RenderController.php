<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Render;
use App\Form\RenderType;
use App\Repository\RenderRepository;

class RenderController extends AbstractController
{
    #[Route('/render', name: 'app_render')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RenderController.php',
        ]);
    }

    #[Route('/Render/View', name: 'ViewR')]
    public function View(ManagerRegistry $mg): Response
    {
        $repo = $mg->getRepository(Render::class);
        $result = $repo->findAll();
    
        return $this->render('render/view.html.twig', [
            'renders' => $result,
        ]);
    }

    #[Route('/Render/Add', name: 'RenderAdd')]
public function Add(ManagerRegistry $mg, Request $req): Response
{
    $render = new Render();
    $form=$this->createForm(RenderType::class, $render);
    $form->handleRequest($req);
    if(($form->isSubmitted() && $form->isValid()))
    {
        $em = $mg->getManager();
        $em->persist($render);
        $em->flush();
        return $this->redirectToRoute('ViewR');
    }
    return $this->renderForm('render/add.html.twig', array('renderForm'=>$form));
}

#[Route('/Render/Update/{id}', name: 'AuthorUpdate')]
public function Update(ManagerRegistry $mg, Request $req, RenderRepository $repo, $id): Response
{
    $render = $mg->getRepository(Render::class)->find($id);
    $form=$this->createForm(RenderType::class, $render);
    $form->handleRequest($req);
    if($form->isSubmitted())
    {
        $em = $mg->getManager();
        $em->flush();
        return $this->redirectToRoute('ViewR');
    }
    return $this->renderForm('render/update.html.twig', array('renderForm'=>$form));
}

#[Route('/Render/Delete/{id}', name: 'RenderDelete')]
public function Delete(ManagerRegistry $mg, $id, Request $req): Response
{
    $render = $mg->getRepository(Render::class)->find($id);
    $em = $mg->getManager();
    $em->remove($render);
    $em->flush();
    return $this->redirectToRoute('ViewR'); 
}

}

