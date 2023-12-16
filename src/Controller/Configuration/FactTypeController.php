<?php

namespace App\Controller\Configuration;

use App\Entity\Configuration\FactType;
use App\Form\Configuration\FactTypeType;
use App\Repository\Configuration\FactTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/configuration/fact-type')]
class FactTypeController extends AbstractController
{
    #[Route('/', name: 'app_configuration_fact_type_index', methods: ['GET'])]
    public function index(FactTypeRepository $factTypeRepository): Response
    {
        return $this->render('configuration/fact_type/index.html.twig', [
            'fact_types' => $factTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_configuration_fact_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factType = new FactType();
        $form = $this->createForm(FactTypeType::class, $factType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factType);
            $entityManager->flush();

            return $this->redirectToRoute('app_configuration_fact_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('configuration/fact_type/new.html.twig', [
            'fact_type' => $factType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_configuration_fact_type_show', methods: ['GET'])]
    public function show(FactType $factType): Response
    {
        return $this->render('configuration/fact_type/show.html.twig', [
            'fact_type' => $factType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_configuration_fact_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactType $factType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactTypeType::class, $factType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_configuration_fact_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('configuration/fact_type/edit.html.twig', [
            'fact_type' => $factType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_configuration_fact_type_delete', methods: ['POST'])]
    public function delete(Request $request, FactType $factType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($factType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_configuration_fact_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
