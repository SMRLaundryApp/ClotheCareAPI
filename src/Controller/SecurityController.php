<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
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
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
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
     * @Route("/api/Users/maker", name="api_user", methods={"POST"})
     */
    public function index(EntityManagerInterface $em, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $userCheck = $this->getDoctrine()->getRepository(user::class)->findOneBy( ['username'=>$data['username'],'email'=>$data['email']]);
        if (empty($userCheck)) {
            $user = new user();
            $user->setEmail($data['email']);
            $user->setUsername($data['username']);
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $data['password']
            ));
            $em->persist($user);
            $em->flush();

            return $this->json([
                'user' => $user,
            ]);
        }else{
            return $this->json([
                'error' => "this user already exists",
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
                'user' => $this->getUser() ? $this->getUser() : null
            ]
        );
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }
}
