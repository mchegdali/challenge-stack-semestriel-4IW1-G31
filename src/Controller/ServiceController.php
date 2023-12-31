<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function createService(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($service);
            $em->flush();
        }

        $services = $doctrine->getManager()->getRepository(Service::class)->findAll();

        return $this->render('default/service.html.twig', [
            'form' => $form->createView(),
            'services' => $services,
        ]);
    }

    #[Route('/service/{id}/delete', name: 'delete_service')]
    public function deleteTax(Request $request, PersistenceManagerRegistry $doctrine, Service $service): Response
    {
        $em = $doctrine->getManager();
        $em->remove($service);
        $em->flush();
    
        return $this->redirectToRoute('app_service');
    }
    
}
