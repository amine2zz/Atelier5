<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AuthorController.php',
        ]);
    }

#[Route('/Author/View', name: 'ViewA')]
public function View(ManagerRegistry $mg): Response
{
    $repo = $mg->getRepository(Author::class);
    $result = $repo->findAll();

    return $this->render('author/view.html.twig', [
        'authors' => $result,
    ]);
}

#[Route('/Author/Add', name: 'AuthorAdd')]
public function Add(ManagerRegistry $mg, Request $req): Response
{
    $author = new Author();
    $form=$this->createForm(AuthorType::class, $author);
    $form->handleRequest($req);
    if(($form->isSubmitted() && $form->isValid()))
    {
        $em = $mg->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('ViewA');
    }
    return $this->renderForm('author/add.html.twig', array('authorForm'=>$form));
}

#[Route('/Author/Update/{id}', name: 'AuthorUpdate')]
public function Update(ManagerRegistry $mg, Request $req, AuthorRepository $repo, $id): Response
{
    $author = $mg->getRepository(Author::class)->find($id);
    $form=$this->createForm(AuthorType::class, $author);
    $form->handleRequest($req);
    if($form->isSubmitted())
    {
        $em = $mg->getManager();
        $em->flush();
        return $this->redirectToRoute('ViewA');
    }
    return $this->renderForm('author/update.html.twig', array('authorForm'=>$form));
}

#[Route('/Author/Delete/{id}', name: 'AuthorDelete')]
public function Delete(ManagerRegistry $mg, $id, Request $req): Response
{
    $author = $mg->getRepository(Author::class)->find($id);
    $em = $mg->getManager();
    $em->remove($author);
    $em->flush();
    return $this->redirectToRoute('ViewA'); 
}

}

