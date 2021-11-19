<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Company;

use App\Form\CompanyType;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company/create", name="company_create")
     */
    public function createCompany(Request $request, EntityManagerInterface $em): Response
    {
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
