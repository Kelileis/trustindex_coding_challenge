<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    #[Route('/', name: 'app_review_index')]
    public function index(ReviewRepository $reviewRepository, Request $request): Response
    {
        $search = $request->query->get('search');
        $reviews = $search
            ? $reviewRepository->findByCompanyName($search)
            : $reviewRepository->findLatestReviews();

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
            'search' => $search,
        ]);
    }

    #[Route('/new-review', name: 'app_review_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Köszönjük a véleményed!');

            return $this->redirectToRoute('app_review_index');
        }

        return $this->render('review/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/review/{id}', name: 'app_review_show', requirements: ['id' => '\d+'])]
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/companies', name: 'app_companies')]
    public function companies(ReviewRepository $reviewRepository): Response
    {
        $statistics = $reviewRepository->getCompanyStatistics();

        return $this->render('review/companies.html.twig', [
            'companies' => $statistics,
        ]);
    }
}
