<?php

namespace App\Controller;

use App\Entity\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;


class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function list(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    
    #[Route('/produit/CreerProduit', name: 'app_produit_creer_produit')]
    public function CreerProduit(EntityManagerInterface $entityManager): Response
    {

        $produit1 = new Produit();
        $produit1->setLibeller("Coca");
        $produit1->setDescription("Soda");
        $produit1->setPrixUnitaire("2");
        $entityManager->persist($produit1);

        $entityManager->flush();

        return new Response("ok"); 
    }
}
