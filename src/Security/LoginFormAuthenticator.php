<?php

// Namespace pour la classe LoginFormAuthenticator
namespace App\Security;

// Importation des classes nécessaires
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

// Classe pour gérer l'authentification via un formulaire de connexion
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    // Utilisation du trait TargetPathTrait pour gérer les chemins cibles
    use TargetPathTrait;

    // Constante pour la route de connexion
    public const LOGIN_ROUTE = 'app_login';

    // Constructeur avec injection de dépendance pour UrlGeneratorInterface
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    // Méthode pour authentifier l'utilisateur
    public function authenticate(Request $request): Passport
    {
        // Récupération de l'email de l'utilisateur
        $email = $request->getPayload()->getString('email');

        // Stockage de l'email dans la session pour rappel ultérieur
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Création d'un Passport contenant les informations d'identification
        return new Passport(
            new UserBadge($email), // Badge pour identifier l'utilisateur
            new PasswordCredentials($request->getPayload()->getString('password')), // Badge pour les crédentiels
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')), // Badge pour la protection CSRF
                new RememberMeBadge(), // Badge pour la fonction "se souvenir de moi"
            ]
        );
    }

    // Méthode appelée lors d'une authentification réussie
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Récupération de l'utilisateur authentifié
        $user = $token->getUser();

        // Redirection différenciée selon le rôle de l'utilisateur
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            // Si l'utilisateur est un administrateur, rediriger vers l'accueil admin
            return new RedirectResponse($this->urlGenerator->generate('admin_accueil'));
        }

        // Sinon, rediriger vers l'accueil client
        return new RedirectResponse($this->urlGenerator->generate('client_accueil'));
    }

    // Méthode pour obtenir l'URL de connexion
    protected function getLoginUrl(Request $request): string
    {
        // Génération de l'URL de connexion
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}