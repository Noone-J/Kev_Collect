<?php

// Namespace de contrôleur
namespace App\Controller;

// Importation des classes nécessaires
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Classe contrôleur pour gérer les utilisateurs et les accueils
class UserController extends AbstractController
{
    // Route pour afficher la liste des utilisateurs (pour l'administrateur)
    #[Route('/admin/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        // Rendu de la vue user/index.html.twig avec tous les utilisateurs
        return $this->render('user/index.html.twig', [
            'users'=>$userRepository->findAll(),
        ]);
    }

    // Route pour l'accueil de l'administrateur
    #[Route('/', name: 'admin_accueil')]
    public function adminAccueil(): Response
    {
        // Rendu de la vue d'accueil pour l'administrateur
        return $this->render('user/admin/accueil.html.twig');
    }

    // Route pour l'accueil du client
    #[Route('/', name: 'client_accueil')]
    public function clientAccueil(): Response
    {
        // Rendu de la vue d'accueil pour le client
        return $this->render('user/client/accueil.html.twig');
    }
}