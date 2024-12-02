<?php

namespace App\Controller;

use App\Entity\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/getProduit', name: 'app_api_Produit_get_all', methods: ['GET'])]
    public function getAllEncheres(): JsonResponse
    {
        return $this->fetchAllProduits();
    }

    private function fetchAllProduits(): JsonResponse
    {
        $Produits = $this->entityManager->getRepository(Produit::class)->findAll();

        $data = [];

        foreach ($Produits as $Produit) {
            $data[] = [
                'id' => $Produit->getId(),
                'libeller' => $Produit->getLibeller(),
                'description' => $Produit->getDescription(),
                'prix_unitaire' => $Produit->getPrix_unitaire(),
                'image' => $Produit->getImage(),
            ];
        }

        return new JsonResponse($data);
    }
}
