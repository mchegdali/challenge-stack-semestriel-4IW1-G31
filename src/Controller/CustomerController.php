<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function createCompany(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($customer);
            $em->flush();
        }

        $customers = $doctrine->getManager()->getRepository(Customer::class)->findAll();

        return $this->render('customer/Customer.html.twig', [
            'form' => $form->createView(),
            'customers' => $customers,
        ]);
    }

    #[Route('/customer_details/{id}', name: 'app_customer_details')]
    public function companyDetails(Request $request, PersistenceManagerRegistry $doctrine, $id): Response
    {
        $customerRepository = $doctrine->getManager()->getRepository(Customer::class);
        
        $customer = $customerRepository->find($id);

        if (!$customer) {
            throw $this->createNotFoundException('Company not found');
        }

        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($customer);
            $em->flush();
        }

        return $this->render('customer/CustomerDetails.html.twig', [
            'form' => $form->createView(),
            'customer' => $customer,
        ]);
    }

    #[Route('/customer/{id}/delete', name: 'delete_customer')]
    public function deleteCustomer(PersistenceManagerRegistry $doctrine, Customer $customer): Response
    {
        $em = $doctrine->getManager();
        $em->remove($customer);
        $em->flush();
    
        return $this->redirectToRoute('app_customer');
    }

}
