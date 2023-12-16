<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'page_name' => 'Home'
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/login.html.twig', [
            'controller_name' => 'HomeController',
            'page_name' => 'Login',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new Exception('logout() should never be reached.');
    }
}
