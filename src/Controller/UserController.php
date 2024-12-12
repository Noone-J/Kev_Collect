<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users'=>$userRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'admin_accueil')]
    public function adminAccueil(): Response
    {
        return $this->render('user/admin/accueil.html.twig');
    }

    #[Route('/', name: 'client_accueil')]
    public function clientAccueil(): Response
    {
        return $this->render('user/client/accueil.html.twig');
    }
}
