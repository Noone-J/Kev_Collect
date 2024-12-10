<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil',methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('acceuil/index.html.twig', [
            'produits'=>$produitRepository->findAll()
        ]);
    }

    #[Route('/poduit/{id}/info', name: 'app_produit_info',methods: ['GET'])]
    public function info(Produit $produit, ProduitRepository $produitRepository): Response
    {

        $dernierProduit = $produitRepository->findBy([],['id'=>'DESC'],limit:4);

        return $this->render('acceuil/info.html.twig', [
            'produit'=>$produit,
            'produits'=>$dernierProduit
        ]);
    }
}
?>