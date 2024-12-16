<?php

namespace App\Controller;

use App\Entity\DetailCommande;
use App\Form\DetailCommandeType;
use App\Repository\DetailCommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/detail/commande')] // Définit le préfixe de route pour toutes les routes de ce contrôleur
final class DetailCommandeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Méthode pour afficher la liste de tous les détails de commande
    #[Route(name: 'app_detail_commande_index', methods: ['GET'])]
    public function index(DetailCommandeRepository $detailCommandeRepository): Response
    {
        return $this->render('detail_commande/index.html.twig', [
            'detail_commandes' => $detailCommandeRepository->findAll(),
        ]);
    }

    // Méthode pour créer un nouveau détail de commande
    #[Route('/new', name: 'app_detail_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $detailCommande = new DetailCommande();
        $form = $this->createForm(DetailCommandeType::class, $detailCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($detailCommande);
            $entityManager->flush();

            return $this->redirectToRoute('app_detail_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('detail_commande/new.html.twig', [
            'detail_commande' => $detailCommande,
            'form' => $form,
        ]);
    }

    // Méthode pour afficher les détails d'un détail de commande spécifique
    #[Route('/{id}', name: 'app_detail_commande_show', methods: ['GET'])]
    public function show(DetailCommande $detailCommande): Response
    {
        return $this->render('detail_commande/show.html.twig', [
            'detail_commande' => $detailCommande,
        ]);
    }

    // Méthode pour éditer un détail de commande existant
    #[Route('/{id}/edit', name: 'app_detail_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DetailCommande $detailCommande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DetailCommandeType::class, $detailCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_detail_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('detail_commande/edit.html.twig', [
            'detail_commande' => $detailCommande,
            'form' => $form,
        ]);
    }

    // Méthode pour supprimer un détail de commande
    #[Route('/{id}', name: 'app_detail_commande_delete', methods: ['POST'])]
    public function delete(Request $request, DetailCommande $detailCommande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detailCommande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($detailCommande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_detail_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    // Méthode pour modifier la quantité d'un produit dans le panier
    #[Route('/panier/modifier/{id}', name: 'app_detail_commande_modifier_quantite')]
    public function modifierQuantite(DetailCommande $detailCommande, int $nouvelleQuantite): Response
    {
        $detailCommande->setQuantite($nouvelleQuantite);
        $detailCommande->setPrixQuantite($detailCommande->getLeProduit()->getPrixUnitaire() * $nouvelleQuantite);

        $this->entityManager->flush();

        return $this->redirectToRoute('app_commande_show', ['id' => $detailCommande->getLaCommande()->getId()]);
    }

    #[Route('/panier/supprimer/{id}', name: 'app_detail_commande_supprimer_produit')]
    public function supprimerDuPanier(DetailCommande $detailCommande): Response
    {
        $commande = $detailCommande->getLaCommande();
        $commande->removeLesDetailsCommande($detailCommande);

        if ($commande->getLesDetailsCommande()->isEmpty()) {
            $this->entityManager->remove($commande);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
    }
}
