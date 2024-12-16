<?php

// Namespace pour la classe EmailVerifier
namespace App\Security;

// Importation des classes nécessaires
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

// Classe pour gérer la vérification des emails des utilisateurs
class EmailVerifier
{
    // Constructeur avec injection de dépendances
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    // Méthode pour envoyer un email de confirmation
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        // Génération de la signature pour l'email de confirmation
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            (string) $user->getEmail()
        );

        // Ajout du lien signé et des informations d'expiration au contexte de l'email
        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        // Mise à jour du contexte de l'email
        $email->context($context);

        // Envoi de l'email
        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    // Méthode pour gérer la confirmation de l'email
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        // Validation de la confirmation de l'email depuis la requête
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string) $user->getId(), (string) $user->getEmail());

        // Marquage de l'utilisateur comme vérifié
        $user->setVerified(true);

        // Persistance et flush des modifications dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}