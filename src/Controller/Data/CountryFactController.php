<?php

namespace App\Controller\Data;

use App\Entity\Data\CountryFact;
use App\Entity\Location\Country;
use App\Form\Data\CountryFactType;
use App\Repository\Data\CountryFactRepository;
use App\Repository\Location\CountryRepository;
use App\Service\GridBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\DBAL\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/data/country-facts')]
class CountryFactController extends AbstractController
{
    #[Route('/{parentId}/', name: 'app_data_country_fact_index', defaults: ['parentId' => 0], methods: ['GET'])]
    public function index(GridBuilder $grid, CountryFactRepository $em, Request $request, $parentId,
                          CountryRepository $countryRepository): Response
    {
        $class = get_class($this);

        $this->denyAccessUnlessGranted('view',$class);

        $page = $request->query->get('page',1);
        $options['sortBy'] = $request->query->get('sortBy');
        $options['sortType'] = $request->query->get('sortType');
        $options['description'] = $request->query->get('description');
        $options['country'] = $parentId;

        $country = $countryRepository->findOneBy(['id'=>$parentId]);

        if ($country instanceof Country) {
            $this->addFlash('info', sprintf('You are managing facts under %s', $country->getName()));
        }

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
        $grid->addGridHeader('Content',null,'text',false);
        $grid->addGridHeader('Fact Type',null,'text',true);
        $grid->addGridHeader('Content Type',null,'text',true);
        $grid->addGridHeader('Is User Created ?',null,'text',false);
        $grid->addGridHeader('Actions',null,'action');
        $grid->setStartIndex($page,$maxPerPage);
        $grid->setParentValue($parentId);
        $grid->setPath('app_data_country_fact_index');
        $grid->setCurrentObject($class);
        $grid->setButtons();

        //Render the output
        return $this->render('main/app.list.html.twig',array(
            'records'=>$dataGrid,
            'grid'=>$grid,
            'page_name'=>'Existing Country Facts',
            'gridTemplate'=>'data/country_fact/index.html.twig'
        ));
    }

    #[Route('/{parentId}/new', name: 'app_data_country_fact_new', defaults: ['parentId' => 0], methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $parentId,
                        CountryRepository $countryRepository): Response
    {
        $countryFact = new CountryFact();
        $form = $this->createForm(CountryFactType::class, $countryFact);
        $form->handleRequest($request);

        $country = $countryRepository->findOneBy(['id'=>$parentId]);

        if ($form->isSubmitted() && $form->isValid()) {
            $countryFact->setCountry($country);
            $countryFact->setIsUserCreated(true);
            $entityManager->persist($countryFact);
            $entityManager->flush();
            return $this->redirectToRoute('app_data_country_fact_index', ['parentId'=>$parentId],
                Response::HTTP_SEE_OTHER);
        }

        $this->addFlash('info', sprintf('You are adding a fact for %s', $country->getName()));

        return $this->render(
            'main/app.form.html.twig',
            array(
                'formTemplate'=>'data/country_fact/form.html.twig',
                'form'=>$form->createView(),
                'isFullWidth'=>true,
                'isAdd'=>true,
                'page_name'=>'Country Fact Details'
            )
        );
    }

    #[Route('/{parentId}/{id}/edit', name: 'app_data_country_fact_edit',  defaults: ['parentId' => 0, 'id' => 0], methods: ['GET', 'POST'])]
    public function edit(Request $request, CountryFact $countryFact,
                         EntityManagerInterface $entityManager, $parentId, CountryRepository $countryRepository
    ): Response
    {
        $form = $this->createForm(CountryFactType::class, $countryFact);
        $form->handleRequest($request);
        $country = $countryRepository->findOneBy(['id'=>$parentId]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Record successfully updated");

            return $this->redirectToRoute('app_data_country_fact_index', ['parentId'=>$parentId], Response::HTTP_SEE_OTHER);
        }

        $this->addFlash('info', sprintf('You are editing a fact for %s', $country->getName()));

        return $this->render(
            'main/app.form.html.twig',
            array(
                'formTemplate'=>'data/country_fact/form.html.twig',
                'form'=>$form->createView(),
                'isFullWidth'=>true,
                'isAdd'=>true,
                'page_name'=>'Country Fact Details'
            )
        );
    }

    #[Route('/{parentId}/{id}/delete/', name: 'app_data_country_fact_delete', methods: ['GET'])]
    public function delete(Request $request, CountryFact $countryFact, EntityManagerInterface $entityManager, $parentId): Response
    {
        $this->addFlash('success', 'Record successfully removed');
        $entityManager->remove($countryFact);
        $entityManager->flush();

        return $this->redirectToRoute('app_data_country_fact_index', ['parentId'=>$parentId],
            Response::HTTP_SEE_OTHER);
    }
}
