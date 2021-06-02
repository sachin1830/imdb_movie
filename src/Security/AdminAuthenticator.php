<?php

namespace App\Security;

use App\Repository\AdminRepository;
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


class AdminAuthenticator extends AbstractFormLoginAuthenticator
{
    private $adminRepository;
    private $router;
    private $csfrToken;
    private $passwordEncoder;

    public function __construct(AdminRepository $adminRepository,
    RouterInterface $router,CsrfTokenManagerInterface $csfrToken,
    UserPasswordEncoderInterface $passwordEncoder)
    
    {
        $this->adminRepository = $adminRepository;
        $this->router = $router;
        $this->csfrToken = $csfrToken;
        $this ->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'admin_login' 
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

        return $this->adminRepository->findOneBy(['email' => $credentials['email']]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token,
         string $providerKey)
    {
        return new RedirectResponse($this->router->generate('admin_panel'));
    }

    public function getLoginUrl()
    {
        return $this->router->generate('admin_login');
    }
}
