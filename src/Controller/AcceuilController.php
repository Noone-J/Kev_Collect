<?php

// Namespace de contrôleur
namespace App\Controller;

// Importation des classes nécessaires
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Classe contrôleur pour gérer l'accueil et les informations sur les produits
class AcceuilController extends AbstractController
{
    // Route pour afficher la page d'accueil
    #[Route('/', name: 'app_acceuil',methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        // Rendu de la vue acceuil/index.html.twig avec tous les produits
        return $this->render('acceuil/index.html.twig', [
            'produits'=>$produitRepository->findAll()
        ]);
    }

    // Route pour afficher les informations d'un produit spécifique et les derniers produits
    #[Route('/poduit/{id}/info', name: 'app_produit_info',methods: ['GET'])]
    public function info(Produit $produit, ProduitRepository $produitRepository): Response
    {
        // Récupération des 4 derniers produits ajoutés
        $dernierProduit = $produitRepository->findBy([],['id'=>'DESC'],limit:4);

        // Rendu de la vue acceuil/info.html.twig avec le produit spécifique et les derniers produits
        return $this->render('acceuil/info.html.twig', [
            'produit'=>$produit,
            'produits'=>$dernierProduit
        ]);
    }
}
?>