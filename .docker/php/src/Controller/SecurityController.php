<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\UserChangeType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder= $encoder;
    }

    /**
     * @Route("/login", name="app_login")
     * @param Request $request
     * @param AuthenticationUtils $utils
     * @param AuthorizationCheckerInterface $authChecker
     * @return HttpFoundation\RedirectResponse|HttpFoundation\Response
     */
    public function login(HttpFoundation\Request $request, AuthenticationUtils $utils, AuthorizationCheckerInterface $authChecker)
    {
        if ($authChecker->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }else {
            $error = $utils->getLastAuthenticationError();
            $lastUsername = $utils->getLastUsername();


            return $this->render('security/login.html.twig', [
                'error' => $error,
                'last_username' => $lastUsername,
                'imgnumber' => rand(1, 6)
            ]);
        }
    }
    /**
     * @Route("/api/login", name="app_api_login", methods={"POST"})
     * @param Request $request
     * @param AuthenticationUtils $utils
     * @param AuthorizationCheckerInterface $authChecker
     * @return HttpFoundation\RedirectResponse|HttpFoundation\Response
     */
    public function api(HttpFoundation\Request $request, AuthenticationUtils $utils, AuthorizationCheckerInterface $authChecker)
    {
        $user = $this->getUser();
        $token = $this->getUser() ? new ApiToken($user) : null;
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], 400);
        }

        return $this->json([
                'user' => $this->getUser() ? $this->getUser()->getId() : null,
                'roles' => $this->getUser() ? $this->getUser()->getRoles() : null,
                'Token' => $token,
            ]
        );
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }
}
