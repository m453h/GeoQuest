<?php

namespace App\Controller\Configuration;

use App\Entity\Configuration\ContentType;
use App\Form\Configuration\ContentTypeType;
use App\Repository\Configuration\ContentTypeRepository;
use App\Service\GridBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\DBAL\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/configuration/content-type')]
class ContentTypeController extends AbstractController
{
    #[Route('/', name: 'app_configuration_content_type_index', methods: ['GET'])]
    public function index(GridBuilder $grid, ContentTypeRepository $em, Request $request): Response
    {
        $class = get_class($this);

        $this->denyAccessUnlessGranted('view',$class);

        $page = $request->query->get('page',1);
        $options['sortBy'] = $request->query->get('sortBy');
        $options['sortType'] = $request->query->get('sortType');
        $options['description'] = $request->query->get('description');

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
        $grid->addGridHeader('Description','description','text',true);
        $grid->addGridHeader('Actions',null,'action');
        $grid->setStartIndex($page,$maxPerPage);
        $grid->setPath('app_configuration_content_type_index');
        $grid->setCurrentObject($class);
        $grid->setButtons();

        //Render the output
        return $this->render('main/app.list.html.twig',array(
            'records'=>$dataGrid,
            'grid'=>$grid,
            'page_name'=>'Existing Content Types',
            'gridTemplate'=>'main/base.list.html.twig'
        ));
    }

    #[Route('/new', name: 'app_configuration_content_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contentType = new ContentType();
        $form = $this->createForm(ContentTypeType::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contentType);
            $entityManager->flush();
            $this->addFlash('success', 'Record successfully created');
            return $this->redirectToRoute('app_configuration_content_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'main/app.form.html.twig',
            array(
                'formTemplate'=>'configuration/content_type/form.html.twig',
                'form'=>$form->createView(),
                'isFullWidth'=>true,
                'isAdd'=>true,
                'page_name'=>'Content Type Details'
            )

        );

    }

    #[Route('/{id}/edit', name: 'app_configuration_content_type_edit', defaults: ['id' => 0], methods: ['GET', 'POST'])]
    public function edit(Request $request, ContentType $contentType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContentTypeType::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Record successfully updated');
            return $this->redirectToRoute('app_configuration_content_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'main/app.form.html.twig',
            array(
                'formTemplate'=>'configuration/content_type/form.html.twig',
                'form'=>$form->createView(),
                'isFullWidth'=>true,
                'isAdd'=>true,
                'page_name'=>'Content Type Details'
            )

        );
    }

    #[Route('/{id}/delete', name: 'app_configuration_content_type_delete', methods: ['GET'])]
    public function delete(ContentType $contentType, EntityManagerInterface $entityManager): Response
    {
        $this->addFlash('success', 'Record successfully removed');
        $entityManager->remove($contentType);
        $entityManager->flush();
        return $this->redirectToRoute('app_configuration_content_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
