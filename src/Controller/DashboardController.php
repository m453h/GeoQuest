<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/app/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        dump($this->generateUrl('app_another'));
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'page_name'=>'Dashboard'
        ]);
    }

    #[Route('/app/another', name: 'app_another')]
    public function another(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'page_name'=>'Dashboard'
        ]);
    }
}
