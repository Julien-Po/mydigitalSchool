<?php

namespace App\Controller;

use App\Form\PaymentType;
use App\Repository\IngredientsRepository;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/mentions', name: 'mentions')]
    public function mentions(): Response
    {
        return $this->render('pages/mentions.html.twig', [
            // 'images' => $images,
        ]);
    }

    #[Route('/story', name: 'app_story')]
    public function story(): Response
    {
        return $this->render('pages/story.html.twig');
    }

    #[Route('/paiement', name:'app_pay')]
    public function payment(RecipesRepository $recipesRepository, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get les recipes du user log
            $recipesByUser = $recipesRepository->findByUser($this->getUser());
            foreach ($recipesByUser as $recipe) {
                $recipe->setPaid(true);

                $em->persist($recipe);
            }
            
            $em->flush();
            // Display a success message or redirect
            $this->addFlash('success', 'Payment successful!');

            return $this->redirectToRoute('payment_success');
        }
        return $this->render('pages/payment.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    
}
