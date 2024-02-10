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
    public function index(InvoiceRepository $invoiceRepository, PaymentRepository $paymentRepository): Response
    {

        //bloc Factures
        $invoiceCount = $invoiceRepository->count([]);
        $invoiceCountLate1 = $invoiceRepository->countLateInvoices1();
        $invoiceCountLate2 = $invoiceRepository->countLateInvoices2();
        $unpaidInvoicesCount = $invoiceRepository->countUnpaindInvoices(); //factures non échues

        //bloc Ventes
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé ou non connecté.');
        }
        $paymentLast12Months = $paymentRepository->findTotalPaymentsForCompany($user->getCompany(), 'last_12_months');
        $paymentThisMonth = $paymentRepository->findTotalPaymentsForCompany($user->getCompany(), 'current_month');

        $amountReceivedAllTimes = $paymentRepository->findTotalPaymentsForCompany($user->getCompany(), 'all_times');
        $totalInvoices = $invoiceRepository->totalInvoicesForCompany($user->getCompany());
        $amountToReceive = $totalInvoices - $amountReceivedAllTimes;

        $companyId = $user->getCompany()->getId();

        for ($i = 11; $i >= 0; $i--) {
            $month = (new \DateTime())->modify("-$i months")->format('m');
            $year = (new \DateTime())->modify("-$i months")->format('Y');
            
            $totalPayments = $paymentRepository->findTotalPaymentsForCompanyInMonth($companyId->toRfc4122(), $month, $year);
            
            $paymentsData[] = $totalPayments ?: 0; // Ajoute 0 si null

            dd($companyId->toRfc4122(), $month, $year, $totalPayments);
        }
    
    

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
            'last12MonthsLabels' => $this->getLast12MonthsLabels(),
            'paymentsData' => $paymentsData,
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
