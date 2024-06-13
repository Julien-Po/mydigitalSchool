<?php

namespace App\Controller;

use App\Form\PaymentType;
use App\Repository\CalendarRepository;
use App\Repository\IngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(IngredientsRepository $ingredientsRepository): Response
    {
        // $projectDir = $this->getParameter('kernel.project_dir');
        // $imagesDir = $projectDir . '/assets/img/entrees/'; 

        // if (!is_dir($imagesDir)) {
        //     throw new \Exception("Le chemin spécifié est introuvable: $imagesDir");
        // }

        // $images = array_diff(scandir($imagesDir), ['..', '.']);
        // $images = array_filter($images, function($file) use ($imagesDir) {
        //     return is_file($imagesDir . $file);
        // });

        // $images = array_map(function($image) {
        //     return 'img/entrees/' . $image;
        // }, $images);
        // $starterIngredient = $ingredientsRepository->findRandomIngredient();
        // dd($starterIngredient);


        return $this->render('home/index.html.twig', [
            // 'images' => $images,
        ]);
    }

    #[Route('/paiement', name:'app_pay')]
    public function payment(): Response
    {
        $form = $this->createForm(PaymentType::class);

        if ($form->isSubmitted()) {
            // Save the payment data to the database (in a real scenario)
          

            // Display a success message or redirect
            $this->addFlash('success', 'Payment successful!');

            return $this->redirectToRoute('payment_success');
        }
        return $this->render('pages/payment.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
