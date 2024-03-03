<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Form\TaxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

#[Route('/tax', name: 'tax_')]
#[IsGranted("ROLE_USER")]
class TaxController extends AbstractController
{
    #[Route('', name: 'index')]
    public function createTax(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $loggedInUser = $this->getUser();

        $tax = new Tax();
        $form = $this->createForm(TaxType::class, $tax);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tax->setCompany($loggedInUser->getCompany());
            $em = $doctrine->getManager();
            $em->persist($tax);
            $em->flush();
        }

        $taxes = $doctrine->getManager()->getRepository(Tax::class)->findAll();

        return $this->render('tax/index.html.twig', [
            'form' => $form->createView(),
            'taxes' => $taxes,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function deleteTax(Request $request, EntityManagerInterface $em, Tax $tax): Response
    {
        $em->remove($tax);
        $em->flush();

        return $this->redirectToRoute('tax_index');
    }

    #[Route('/{id}/update', name: 'update')]
    public function updateTax(Request $request, PersistenceManagerRegistry $doctrine, Tax $tax): Response
    {
        $form = $this->createForm(TaxType::class, $tax);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('tax_index');
        }

        return $this->render('default/UpdateTax.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
