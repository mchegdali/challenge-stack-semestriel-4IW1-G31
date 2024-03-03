<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/service', name: 'service_')]
#[IsGranted('ROLE_USER')]
class ServiceController extends AbstractController
{
    #[Route('', name: 'index')]
    public function createService(Request $request, ServiceRepository $serviceRepository, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($service);
            $em->flush();
        }

        $services = $serviceRepository->findAllNotArchived();

        $services = $paginator->paginate(
            $services,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('service/service.html.twig', [
            'form' => $form->createView(),
            'services' => $services,
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
