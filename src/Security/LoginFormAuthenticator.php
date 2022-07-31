<?php

namespace App\Security;

use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private UserPasswordHasherInterface $passwordHasher;
    private $flashy;

    public function __construct(UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $passwordHasher, FlashyNotifier $flashy)
    {
        $this->urlGenerator = $urlGenerator;
        $this->passwordHasher = $passwordHasher;
        $this->flashy = $flashy;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

    
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

       
       // $request->getSession()->getFlashBag()->add(
        //    'success', 
         //   'Welcom ' . $token->getUser()->getFullName(). '!');

            $this->flashy->success('Bienvenue '.  $token->getUser()->getFullName() . '!');
     
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

        /**
     * Override to control what happens when the user hits a secure page
     * but isn't logged in yet.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {

        $request->getSession()->getFlashBag()->add('error', 'You need to log in first!');
        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }
}
