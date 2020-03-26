<?php

namespace App\Controller;

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
     */
    public function login(HttpFoundation\Request $request, AuthenticationUtils $utils, AuthorizationCheckerInterface $authChecker)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        if (true === $authChecker->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('AdminDashboard');
        }elseif (true === $authChecker->isGranted('ROLE_SUPER-USER')) {
            return $this->redirectToRoute('dashboard');
        }elseif (true === $authChecker->isGranted('ROLE_SUPER-USER')) {
            return $this->redirectToRoute('dashboard');
        }else {
            return $this->render('security/login.html.twig', [
                'error' => $error,
                'last_username' => $lastUsername,
                'imgnumber' => rand(1, 6)
            ]);
        }
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }
}
