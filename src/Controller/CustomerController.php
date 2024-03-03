<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/customer', name: 'customer_')]
class CustomerController extends AbstractController
{
    #[Route('', name: 'index')]
    public function createCompany(Request $request, EntityManagerInterface $em, CustomerRepository $customerRepository, PaginatorInterface $paginator): Response
    {
        $customer = new Customer();
        $customerForm = $this->createForm(CustomerType::class, $customer);

        $customerForm->handleRequest($request);


        $company = null;

        if ($this->isGranted('ROLE_ADMIN')) {
            $customers = $invoiceRepository->findAll();
        } else {
            $company = $this->getUser()->getCompany();
            if (!$company) {
                throw $this->createNotFoundException('Entreprise non trouvée');
            }
            $customers = $customerRepository->findBy(['company' => $company]);
        }
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $customer->setCompany($this->getUser()->getCompany());
            $em->persist($customer);
            $em->flush();
        }

       

        $customers = $paginator->paginate(
            $customers,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('customer/Customer.html.twig', [
            'customerForm' => $customerForm->createView(),
            'customers' => $customers,
            'typeDocument' => 'customer'
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
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

        return $this->render('customer/show.html.twig', [
            'form' => $form->createView(),
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function deleteCustomer(PersistenceManagerRegistry $doctrine, Customer $customer): Response
    {
        $em = $doctrine->getManager();
        $em->remove($customer);
        $em->flush();

        return $this->redirectToRoute('customer_index');
    }

}
