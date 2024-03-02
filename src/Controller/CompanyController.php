<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

#[Route('/company', name: 'company_')]
#[IsGranted("ROLE_ADMIN")]
class CompanyController extends AbstractController
{
    #[Route('', name: 'index')]
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
            '_form' => $form->createView(),
            'companys' => $companys,
        ]);
    }

    #[Route('/company-details/{id}', name: 'details')]
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
            '_form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function deleteCompany(PersistenceManagerRegistry $doctrine, Company $company): Response
    {
        $em = $doctrine->getManager();
        $em->remove($company);
        $em->flush();

        return $this->redirectToRoute('company_index');
    }
}
