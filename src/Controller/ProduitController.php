<?php

// Namespace de contrôleur
namespace App\Controller;

// Utilisations des entités, formulaires, repositories et services nécessaires
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

// Route racine pour le contrôleur Produit
#[Route('/admin/produit')]
final class ProduitController extends AbstractController
{
    // Route pour afficher la liste des produits
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        // Rendu de la vue produit/index.html.twig avec tous les produits
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    // Route pour créer un nouveau produit
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // Création d'un nouvel objet Produit
        $produit = new Produit();
        
        // Création du formulaire ProduitType
        $form = $this->createForm(ProduitType::class, $produit);
        
        // Gestion des données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement de l'image si elle est fournie
            $image = $form->get('image')->getData();

            if ($image){
                // Génération d'un nom sécurisé pour l'image
                $nomImage = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($nomImage);
                $newFileNom = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                try{
                    // Déplacement de l'image vers le dossier spécifié
                    $image->move(
                        $this->getParameter('image_dir'),
                        $newFileNom
                    );
                }catch (FileException $exception){}

                // Attribution de l'image au produit
                $produit->setImage($newFileNom);
            }
            
            // Persistance et flush du produit dans la base de données
            $entityManager->persist($produit);
            $entityManager->flush();

            // Ajout d'un message de succès et redirection vers la liste des produits
            $this->addFlash(type:'success', message:'Votre produit a bien été ajouté !');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendu de la vue produit/new.html.twig avec le formulaire
        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // Route pour afficher les détails d'un produit spécifique
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        // Rendu de la vue produit/show.html.twig avec les détails du produit
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    // Route pour modifier un produit existant
    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire ProduitType pour le produit existant
        $form = $this->createForm(ProduitType::class, $produit);
        
        // Gestion des données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Flush des modifications dans la base de données
            $entityManager->flush();
            
            // Ajout d'un message de succès et redirection vers la liste des produits
            $this->addFlash(type:'success', message:'Votre produit a bien été modifier !');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendu de la vue produit/edit.html.twig avec le formulaire
        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // Route pour supprimer un produit
    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            // Suppression du produit et flush dans la base de données
            $entityManager->remove($produit);
            $entityManager->flush();

            // Ajout d'un message de danger et redirection vers la liste des produits
            $this->addFlash(type:'danger', message:'Votre produit a bien été supprimer !');

        }

        // Redirection vers la liste des produits
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}