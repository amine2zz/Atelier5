<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BookController.php',
        ]);
    }

#Controller

#[Route('/Book/View', name: 'ViewB')]
public function View(EntityManagerInterface $entityManager): Response
{
    $repository = $entityManager->getRepository(Book::class);

    $queryBuilder = $repository->createQueryBuilder('b')
        ->orderBy('b.title', 'ASC');

    $query = $queryBuilder->getQuery();
    $books = $query->getResult();

    // Count the number of unpublished books
    $unpublishedBooksCount = 0;
    foreach ($books as $book) {
        if (!$book->isPublished()) {
            $unpublishedBooksCount++;
        }
    }

    return $this->render('book/view.html.twig', [
        'books' => $books,
        'unpublishedBooksCount' => $unpublishedBooksCount,
    ]);
}


#[Route('/Book/Add', name: 'BookAdd')]
public function Add(ManagerRegistry $mg, Request $req): Response
{
    $book = new Book();
    $form=$this->createForm(BookType::class, $book);
    $form->handleRequest($req);
    if(($form->isSubmitted() && $form->isValid()))
    {
        $em = $mg->getManager();
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('ViewB');
    }
    return $this->renderForm('book/add.html.twig', array('bookForm'=>$form));
}

#[Route('/Book/Update/{id}', name: 'BookUpdate')]
public function Update(ManagerRegistry $mg, Request $req, BookRepository $repo, $id): Response
{
    $book = $mg->getRepository(Book::class)->find($id);
    $form=$this->createForm(BookType::class, $book);
    $form->handleRequest($req);
    if($form->isSubmitted())
    {
        $em = $mg->getManager();
        $em->flush();
        return $this->redirectToRoute('ViewB');
    }
    return $this->renderForm('book/update.html.twig', array('bookForm'=>$form));
}

#[Route('/Book/Delete/{id}', name: 'BookDelete')]
public function Delete(ManagerRegistry $mg, $id, Request $req): Response
{
    $book = $mg->getRepository(Book::class)->find($id);
    $em = $mg->getManager();
    $em->remove($book);
    $em->flush();
    return $this->redirectToRoute('ViewB'); 
}


    /**
     * @Route("/search-books", name="search_books")
     */
    public function searchBooks(Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $books = []; // Place your book retrieval logic here

        return $this->render('book/search.html.twig', [
            'form' => $form->createView(),
            'books' => $books,
        ]);
    }

}

