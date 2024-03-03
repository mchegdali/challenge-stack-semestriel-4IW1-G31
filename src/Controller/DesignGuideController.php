<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DesignGuideController extends AbstractController
{
    #[Route('/design-guide', name: 'design_guide')]
    public function index(): Response
    {
        return $this->render('design_guide/index.html.twig', [
            
        ]);
    }
}
