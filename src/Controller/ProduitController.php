<?php

namespace App\Controller;

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

#[Route('/admin/produit')]
final class ProduitController extends AbstractController
{
    // Méthode pour afficher la liste de tous les produits
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        // Rendu de la vue index.html.twig avec tous les produits
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    // Méthode pour créer un nouveau produit
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // Création d'un nouvel objet Produit
        $produit = new Produit();
        
        $form = $this->createForm(ProduitType::class, $produit);
        
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'image uploadée
            $image = $form->get('image')->getData();

            if ($image){
                $nomImage = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($nomImage);
                $newFileNom = $safeFileName . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_dir'),
                        $newFileNom
                    );
                } catch (FileException $exception) {
                    // Gestion de l'erreur si le déplacement échoue
                }

                // Enregistrement du nom de l'image dans l'objet Produit
                $produit->setImage($newFileNom);
            }
            
            $entityManager->persist($produit);
            $entityManager->flush();
            // Ajout d'un message flash de succès
            $this->addFlash('type', 'success', 'Votre produit a bien été ajouté !');

            $this->addFlash('type', 'success', 'Votre produit a bien été ajouté !');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendu de la vue new.html.twig avec le formulaire non validé
        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // Méthode pour afficher les détails d'un produit
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        // Rendu de la vue show.html.twig avec le produit sélectionné
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    // Méthode pour éditer un produit existant
    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire pour le produit
        $form = $this->createForm(ProduitType::class, $produit);
        
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            
            $this->addFlash('type', 'success', 'Votre produit a bien été modifié !');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendu de la vue edit.html.twig avec le formulaire non validé
        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // Méthode pour supprimer un produit
    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();

            $this->addFlash('type', 'danger', 'Votre produit a bien été supprimé !');

        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
