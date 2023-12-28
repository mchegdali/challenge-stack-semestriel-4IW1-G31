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

       
        return $this->render('default/service.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
