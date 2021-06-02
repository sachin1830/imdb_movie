<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class UserAuthenticator extends AbstractFormLoginAuthenticator
{

    private $userRepository;
    private $router;
    private $csfrToken;
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository,
    RouterInterface $router,CsrfTokenManagerInterface $csfrToken,
    UserPasswordEncoderInterface $passwordEncoder)
    
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csfrToken = $csfrToken;
        $this ->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'user_login' 
        && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token')
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if(!$this->csfrToken->isTokenValid($token))
        {
            throw new InvalidCsrfTokenException();
        }

        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse($this->router->generate('load_movies'));
    }

    public function getLoginUrl()
    {
        return $this->router->generate('user_login');
    }
}
