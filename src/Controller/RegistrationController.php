<?php

// Namespace de contrôleur
namespace App\Controller;

// Importation des classes nécessaires
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

// Classe contrôleur pour gérer l'inscription des utilisateurs
class RegistrationController extends AbstractController
{
    // Constructeur du contrôleur avec injection de dépendance pour EmailVerifier
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    // Route pour la page d'inscription
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel objet User
        $user = new User();
        
        // Création du formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        // Gestion des données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Hashage du mot de passe en clair
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Persistance et flush du nouvel utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi d'un email de vérification à l'utilisateur
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('drivekev44@gmail.com', 'Service d\'inscription'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // Connexion automatique de l'utilisateur après inscription
            return $security->login($user, LoginFormAuthenticator::class, 'main');
        }

        // Rendu du formulaire d'inscription si le formulaire n'est pas soumis ou invalide
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    // Route pour vérifier l'email de l'utilisateur
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        // Vérification que l'utilisateur est pleinement authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            /** @var User $user */
            $user = $this->getUser();
            
            // Gestion de la confirmation de l'email
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            // Ajout d'un message d'erreur en cas d'échec de la vérification
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            // Redirection vers la page d'inscription en cas d'échec
            return $this->redirectToRoute('app_register');
        }

        // Ajout d'un message de succès après vérification réussie
        $this->addFlash('success', 'Your email address has been verified.');

        // Redirection vers la page d'inscription après vérification
        return $this->redirectToRoute('app_register');
    }
}