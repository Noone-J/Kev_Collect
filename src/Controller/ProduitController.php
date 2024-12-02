<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    // #[Route('/produits', name: 'produit_list')]
    // public function list(): Response
    // {
    //     // Récupérer tous les produits depuis le repository de l'entité Produit
    //     $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();

    //     // Renvoyer les produits à la vue Twig
    //     return $this->render('produit/list.html.twig', [
    //         'produits' => $produits,
    //     ]);
    // }
}