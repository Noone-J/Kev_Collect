<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\Produit;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    private $entityManager;

    // Constructeur pour initialiser l'EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Méthode pour afficher la liste de toutes les commandes
    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    // Méthode pour créer une nouvelle commande
    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $commande->setDate(new \DateTime());

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    // Méthode pour calculer et mettre à jour le prix total d'une commande
    #[Route('/setPrixTotal/{id}', name: 'app_commande_set_prix_total', methods: ['GET', 'POST'])]
    public function setPrixTotal(Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $commandeTotaux = 0;
        
        // Calcul du prix total en additionnant les prix de tous les détails de commande
        foreach ($commande->getLesDetailsCommande() as $leDetailsCommande) {
            $commandeTotaux += $leDetailsCommande->getPrixQuantite();
        }
        
        $commande->setPrixCommande($commandeTotaux);
        
        $entityManager->flush();
        
        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
    }

    // Méthode pour afficher les détails d'une commande spécifique
    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    // Méthode pour éditer une commande existante
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

    // Méthode pour supprimer une commande
    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    // Méthode pour afficher le panier de l'utilisateur connecté
    #[Route('/panier', name: 'app_commande_afficher_panier')]
    public function afficherPanier(CommandeRepository $commandeRepository): Response
    {
        $user = $this->getUser();
        $commande = $commandeRepository->findOneBy(['leUser' => $user, 'leStatut' => 'En cours']);

        if (!$commande) {
            // Si aucune commande en cours, afficher le panier vide
            return $this->render('commande/panier_vide.html.twig');
        }

        // Afficher le contenu du panier
        return $this->render('commande/panier.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_commande_ajouter_produit')]
    public function ajouterProduitAuPanier(
        Produit $produit,
        CommandeRepository $commandeRepository,
        StatutRepository $statutRepository
    ): Response
    {
        $user = $this->getUser();
        $commande = $commandeRepository->findOneBy([
            'leUser' => $user,
            'leStatut' => $statutRepository->findOneBy(['libelle' => 'En cours'])
        ]);

        if (!$commande) {
            // Création d'une nouvelle commande si elle n'existe pas
            $commande = new Commande();
            $commande->setLeUser($user);
            $commande->setLeStatut($statutRepository->findOneBy(['libelle' => 'En cours']));
            $commande->setDate(new \DateTime());
            $commande->setPrixCommande(0); // Initialise le prix à 0
            $this->entityManager->persist($commande);

        // Création d'un nouveau détail de commande
        $detailCommande = new DetailCommande();
        $detailCommande->setLaCommande($commande);
        $detailCommande->setLeProduit($produit);
        $detailCommande->setQuantite(1);
        $detailCommande->setPrixQuantite($produit->getPrixUnitaire());

        // Ajout du détail de commande à la commande
        $commande->addLesDetailsCommande($detailCommande);

        // Mise à jour du prix total de la commande
        $commande->setPrixCommande($commande->getPrixCommande() + $detailCommande->getPrixQuantite());

        $this->entityManager->persist($commande);

        return $this->redirectToRoute('app_commande_afficher_panier');
    }

//     /**
//  * Modifie la quantité d'un produit dans le panier.
//  *
//  * @param DetailCommande $detailCommande
//  * @param Request $request
//  * @return JsonResponse
//  */
// public function modifierQuantite(DetailCommande $detailCommande, Request $request): JsonResponse
// {
//     $nouvelleQuantite = $request->request->get('nouvelleQuantite');

//     if ($nouvelleQuantite > 0) {
//         $detailCommande->setQuantite($nouvelleQuantite);
//         $detailCommande->setPrixQuantite($detailCommande->getLeProduit()->getPrixUnitaire() * $nouvelleQuantite);
        
//         $this->entityManager->flush();

//         return new JsonResponse(['message' => 'Quantité modifiée avec succès'], 200);
//     }

//     return new JsonResponse(['error' => 'La quantité doit être positive'], 400);
// }


//     #[Route('/panier/supprimer-produit/{id}', name: 'app_detail_commande_supprimer_produit')]
//     public function supprimerProduitDuPanier(DetailCommande $detailCommande): Response
//     {
//         $commande = $detailCommande->getLaCommande();
//         $commande->removeLesDetailsCommande($detailCommande);

//         $this->entityManager->remove($detailCommande);
//         $this->entityManager->flush();

//         return $this->redirectToRoute('app_commande_afficher_panier');
//     }
}