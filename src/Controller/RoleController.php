<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class RoleController extends AbstractController
{
    #[Route('/role', name: 'app_role')]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $role = new Role();

        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($role);
            $em->flush();
        }

        $roles=$doctrine->getManager()->getRepository(Role::class)->findAll();

        return $this->render('role/index.html.twig', [
            'form' => $form->createView(),
            'roles' =>  $roles,
        ]);
    }
}
