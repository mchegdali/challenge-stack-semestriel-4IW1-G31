<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Form\TaxType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class TaxController extends AbstractController
{
    #[Route('/tax', name: 'create_tax')]
    public function createTax(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $tax = new Tax();
        $form = $this->createForm(TaxType::class, $tax);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($tax);
            $em->flush();
        }

        $taxs = $doctrine->getManager()->getRepository(Tax::class)->findAll();

        return $this->render('tax/tax.html.twig', [
            'form' => $form->createView(),
            'taxs' => $taxs,
        ]);
    }

    #[Route('/tax/{id}/delete', name: 'delete_tax')]
    public function deleteTax(Request $request, PersistenceManagerRegistry $doctrine, Tax $tax): Response
    {
        $em = $doctrine->getManager();
        $em->remove($tax);
        $em->flush();
    
        return $this->redirectToRoute('create_tax');
    }

    #[Route('/tax/{id}/update', name: 'update_tax')]
    public function updateTax(Request $request, PersistenceManagerRegistry $doctrine, Tax $tax): Response
    {
        $form = $this->createForm(TaxType::class, $tax);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('create_tax');
        }
    
        return $this->render('default/UpdateTax.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
