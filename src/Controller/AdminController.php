<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
#[IsGranted("ROLE_ADMIN")]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        CompanyRepository $companyRepository,
        Request $request,
        PaginatorInterface $paginator): Response
    {
        $companies = $companyRepository->findAll();

        $companies = $paginator->paginate(
            $companies,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/dashboard.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/company/{id}', name: 'company')]
    public function company(
        CompanyRepository $companyRepository, 
        $id,
        Request $request,
        PaginatorInterface $paginator): Response
    {
        $company = $companyRepository->find($id);

        $users = $company->getUsers();

        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/company.html.twig', [
            'company' => $company,
            'users' => $users,
        ]);
    }
}
