<?php

namespace App\Controller;

use App\Entity\Tax;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        $nombreDeFactures = $invoiceRepository->count([]);

        return $this->render('default/index.html.twig', [
            'nombreDeFactures' => $nombreDeFactures,
        ]);
    }

}
