<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CommandeRepository $commandeRepository, StatutRepository $statutRepository): Response
    {
        // Récupérer le statut 'en cour'
        $statutEnCour = $statutRepository->findOneBy(['libeller' => 'en cour']);

        // Vérifier si une commande 'en cour' existe déjà
        $commandeEnCour = $commandeRepository->findOneBy(['leStatut' => $statutEnCour]);

        if ($commandeEnCour) {
            return $this->render('commande/show.html.twig', [
                'commande' => $commandeEnCour,
            ]);
    }

        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setPrixCommande(0);
        $commande->setLeStatut($statutEnCour);

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a bien été créée !');

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/setPrixTotal/{id}', name: 'app_commande_set_prix_total', methods: ['GET', 'POST'])]
    public function setPrixTotal(Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $commandeTotaux = 0;
        
        foreach ($commande->getLesDetailsCommande() as $leDetailsCommande) {
            $commandeTotaux += $leDetailsCommande->getPrixQuantite();
        }
        
        $commande->setPrixCommande($commandeTotaux);
        
        $entityManager->flush();
        
        return new Response((string)$commandeTotaux, Response::HTTP_OK, ['Content-Type' => 'text/plain']);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
