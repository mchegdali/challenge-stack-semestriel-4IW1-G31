<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceSearchType;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/services', name: 'service_')]
#[IsGranted('ROLE_COMPANY')]
class ServiceController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request, ServiceRepository $serviceRepository, PaginatorInterface $paginator): Response
    {
        $loggedInUser = $this->getUser();

        $filterForm = $this->createForm(ServiceSearchType::class);
        $filterForm->handleRequest($request);

        if ($this->isGranted('ROLE_ADMIN')) {
            $servicesQuery = $serviceRepository->createQueryBuilder('u'); // Use QueryBuilder for admin
        } else {
            $servicesQuery = $serviceRepository->createQueryBuilder('u')
                ->where('u.company = :companyName')
                ->setParameter('companyName', $loggedInUser->getCompany());
        }

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $isArchived = $filterForm->get('isArchived')->getData();

            if ($isArchived !== null) {
                $servicesQuery->andWhere('u.isArchived = :isArchived')
                    ->setParameter('isArchived', $isArchived);
            }
        }

        $services = $paginator->paginate(
            $servicesQuery, // Remove ->getQuery()
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('service/index.html.twig', [
            'filterForm' => $filterForm->createView(),
            'services' => $services,
        ]);
    }



    #[Route('/new', name: 'new')]
    public function createService(Request $request, EntityManagerInterface $em): Response
    {
        $loggedInUser = $this->getUser();

        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setCompany($loggedInUser->getCompany());
            $em->persist($service);
            $em->flush();

            $this->addFlash('success', 'Service ajouté avec succès.');
            return $this->redirectToRoute('service_index');
        }

        return $this->render('service/add_service.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function deleteTax(Request $request, EntityManagerInterface $em, Service $service): Response
    {
        $em->remove($service);
        $em->flush();

        return $this->redirectToRoute('service_index');
    }

    #[Route('/{id}/update', name: 'update')]
    public function updateService(Request $request, EntityManagerInterface $em, Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('service_index');
        }

        return $this->render('default/UpdateService.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/archive', name: 'archive')]
    public function archiveService(EntityManagerInterface $em, Service $service): Response
    {
        $service->setIsArchived(true);
        $em->flush();

        return $this->redirectToRoute('service_index');
    }
}
