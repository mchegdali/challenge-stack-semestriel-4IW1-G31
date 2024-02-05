<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentSearchType;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payments', name: 'payment_')]
class PaymentController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        PaymentRepository $paymentRepository,
        Request           $request
    ): Response
    {
        $payments = $paymentRepository->findAll();

        return $this->render('payment/index.html.twig', [
            'payments' => $payments
        ]);
    }

    #[Route('/search', name: 'search', methods: ['GET', 'POST'])]
    public function search(
        PaymentRepository $paymentRepository,
        Request           $request
    ): Response
    {
        $form = $this->createForm(PaymentSearchType::class, null, ["method" => "POST"]);

        if ($request->isMethod("GET")) {
            return $this->render('payment/search.html.twig', [
                "searchForm" => $form->createView()
            ]);
        }

        $searchResult = $request->request->all("payment_search");
        
        if (!empty($searchResult)) {
            $payments = $paymentRepository->findBySearch($searchResult);
        } else {
            $payments = $paymentRepository->findAll();
        }

        return $this->redirectToRoute('payment_index', [
            'payments' => $payments,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(): Response
    {
        return $this->render('payment/new.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Payment $payment): Response
    {
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function update(): Response
    {
        return $this->render('payment/edit.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/paymentslist', name: 'payment_list')]
    public function list(PaymentRepository $paymentRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $company = $user->getCompany();
        if (!$company) {
            throw $this->createNotFoundException('Entreprise non trouvée.');
        }

        $payments = $paymentRepository->findBy(['company' => $company]);

        return $this->render('payment/list.html.twig', [
            'payments' => $payments,
        ]);
    }
}
