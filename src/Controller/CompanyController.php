<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function createCompany(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($company);
            $em->flush();
        }

        $companys = $doctrine->getManager()->getRepository(Company::class)->findAll();

        return $this->render('company/Company.html.twig', [
            'form' => $form->createView(),
            'companys' => $companys,
        ]);
    }

    #[Route('/company-details/{id}', name: 'app_company_details')]
    public function companyDetails(Request $request, PersistenceManagerRegistry $doctrine, $id): Response
    {
        $companyRepository = $doctrine->getManager()->getRepository(Company::class);
        
        $company = $companyRepository->find($id);

        if (!$company) {
            throw $this->createNotFoundException('Company not found');
        }

        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($company);
            $em->flush();
        }

        return $this->render('company/CompanyDetails.html.twig', [
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/company/{id}/delete', name: 'delete_company')]
    public function deleteCompany(Request $request, PersistenceManagerRegistry $doctrine, Company $company): Response
    {
        $em = $doctrine->getManager();
        $em->remove($company);
        $em->flush();
    
        return $this->redirectToRoute('app_company');
    }

}
