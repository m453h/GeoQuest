<?php

namespace App\Controller\Location;

use App\Entity\Location\SubRegion;
use App\Form\Location\SubRegionType;
use App\Repository\Location\SubRegionRepository;
use App\Service\GridBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\DBAL\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/location/sub-region')]
class SubRegionController extends AbstractController
{
    #[Route('/', name: 'app_location_sub_region_index', methods: ['GET'])]
    public function index(GridBuilder $grid, SubRegionRepository $em, Request $request): Response
    {
        $class = get_class($this);

        $this->denyAccessUnlessGranted('view',$class);

        $page = $request->query->get('page',1);
        $options['sortBy'] = $request->query->get('sortBy');
        $options['sortType'] = $request->query->get('sortType');
        $options['description'] = $request->query->get('description');
        $options['region'] = $request->query->get('region');

        $maxPerPage = $this->getParameter('grid_per_page_limit');

        $qb1 = $em->getAll($options);

        $qb2 = $em->countAll($qb1);

        $adapter =new QueryAdapter($qb1,$qb2);
        $dataGrid = new Pagerfanta($adapter);
        $dataGrid->setMaxPerPage($maxPerPage);
        $dataGrid->setCurrentPage($page);
        $dataGrid->getCurrentPageResults();

        //Configure the grid
        $grid->addGridHeader('S/N',null,'index');
        $grid->addGridHeader('Name','name','text',true);
        $grid->addGridHeader('Region','region','text',true);
        $grid->addGridHeader('Actions',null,'action');
        $grid->setStartIndex($page,$maxPerPage);
        $grid->setPath('app_location_sub_region_index');
        $grid->setCurrentObject($class);
        $grid->setButtons();

        //Render the output
        return $this->render('main/app.list.html.twig',array(
            'records'=>$dataGrid,
            'grid'=>$grid,
            'page_name'=>'Existing Sub-Regions',
            'gridTemplate'=>'main/base.list.html.twig'
        ));
    }

    #[Route('/new', name: 'app_location_sub_region_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subRegion = new SubRegion();
        $form = $this->createForm(SubRegionType::class, $subRegion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subRegion);
            $entityManager->flush();
            $this->addFlash('success', 'Record successfully created');
            return $this->redirectToRoute('app_location_sub_region_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'main/app.form.html.twig',
            array(
                'formTemplate'=>'location/sub_region/form.html.twig',
                'form'=>$form->createView(),
                'isFullWidth'=>true,
                'isAdd'=>true,
                'page_name'=>'Sub-Region Details'
            )
        );
    }

    #[Route('/{id}/edit', name: 'app_location_sub_region_edit', defaults: ['id' => 0], methods: ['GET', 'POST'])]
    public function edit(Request $request, SubRegion $subRegion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubRegionType::class, $subRegion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Record successfully updated');
            return $this->redirectToRoute('app_location_sub_region_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render(
            'main/app.form.html.twig',
            array(
                'formTemplate'=>'location/sub_region/form.html.twig',
                'form'=>$form->createView(),
                'isFullWidth'=>true,
                'isAdd'=>true,
                'page_name'=>'Sub-Region Details'
            )
        );
    }

    #[Route('/{id}/delete/', name: 'app_location_sub_region_delete', methods: ['GET'])]
    public function delete(SubRegion $subRegion, EntityManagerInterface $entityManager): Response
    {
        $this->addFlash('success', 'Record successfully removed');
        $entityManager->remove($subRegion);
        $entityManager->flush();
        return $this->redirectToRoute('app_location_sub_region_index', [], Response::HTTP_SEE_OTHER);
    }
}
