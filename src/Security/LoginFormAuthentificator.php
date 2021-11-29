<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;

class LoginFormAuthentificator extends AbstractGuardAuthenticator
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'security_login' && $request->isMethod('Post'); //Requipère la route et retourne true si la methode utiliser est Post.
    }

    public function getCredentials(Request $request)
    {
        return $request->request->get('login'); // retourne un tableau avec 3 informations
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {

            return $userProvider->loadUserByUsername($credentials['email']); //userProvider va aller chercher dans notre base de si on a un utilisateur qui correspond à l'email qu'on a rentrer.

        } catch (UsernameNotFoundException $e) {

            throw new AuthenticationException("Cette adresse Email n'est pac connue");
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Verifier que le mot de passe fourni, correspond bien au mot de passe de la base de données.
        //$credentials['password'] => $user->getPassword()

        $isValide = $this->encoder->isPasswordValid($user, $credentials['password']);

        if (!$isValide) {

            throw new AuthenticationException("Les informations de connexion ne correspondent pas");
        }
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse('/');
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
