<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Repository\InvoiceRepository;
use App\Repository\PaymentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    #[IsGranted('ROLE_COMPTABLE')]
    public function index(InvoiceRepository $invoiceRepository, PaymentRepository $paymentRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé ou non connecté.');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('admin_dashboard');
        }

        //bloc Factures
        $invoiceCount = $invoiceRepository->count([]);
        $invoiceCountLate1 = $invoiceRepository->countLateInvoices1();
        $invoiceCountLate2 = $invoiceRepository->countLateInvoices2();
        $unpaidInvoicesCount = $invoiceRepository->countUnpaindInvoices(); //factures non échues

        //bloc Ventes

        $paymentLast12Months = $paymentRepository->findTotalPaymentsForCompany($user->getCompany(), 'last_12_months');
        $paymentThisMonth = $paymentRepository->findTotalPaymentsForCompany($user->getCompany(), 'current_month');

        $amountReceivedAllTimes = $paymentRepository->findTotalPaymentsForCompany($user->getCompany(), 'all_times');
        $totalInvoices = $invoiceRepository->totalInvoicesForCompany($user->getCompany());
        $amountToReceive = $totalInvoices - $amountReceivedAllTimes;

        $companyId = $user->getCompany()->getId()->toRfc4122();

        for ($i = 11; $i >= 0; $i--) {
            $month = (new \DateTime())->modify("-$i months")->format('m');
            $year = (new \DateTime())->modify("-$i months")->format('Y');
            
            $totalPayments = $paymentRepository->findTotalPaymentsForCompanyInMonth($companyId, $month, $year);
            
            $paymentsData[] = $totalPayments ?: 0; // Ajoute 0 si null
        }
        $jsonDataPayment = json_encode($paymentsData);

        $json12Months = json_encode($this->getLast12MonthsLabels());
    

        return $this->render('default/index.html.twig', [
            'invoiceCount' => $invoiceCount,
            'invoiceCountLate1' => $invoiceCountLate1,
            'invoiceCountLate2' => $invoiceCountLate2,
            'unpaidInvoicesCount' => $unpaidInvoicesCount,
            'paymentThisMonth' => $paymentThisMonth,
            'paymentLast12Months' => $paymentLast12Months,
            'amountToReceive' => $amountToReceive,
            'totalInvoices' => $totalInvoices,
            'amountReceivedAllTimes' => $amountReceivedAllTimes,
            'json12Months' => $json12Months,
            'paymentsDataJsonPayment' => $jsonDataPayment
        ]);
    }

    function getLast12MonthsLabels(): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = (new \DateTime())->modify("-$i months");
            
            $months[] = [$date->format('F'), $date->format('Y')];
        }
        return $months;
    }


}
