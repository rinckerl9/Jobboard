<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Entity\Ad;
use App\Entity\Company;

use App\Form\Database\UserType;
use App\Form\Database\CompanyType;
use App\Form\Database\AdType;
use App\Form\RegistrationFormType;

class DatabaseController extends AbstractController
{
    /**
     * @Route("/database", name="database")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        $ads = $em->getRepository(Ad::class)->findAll();
        $companies = $em->getRepository(Company::class)->findAll();

        return $this->render('database/index.html.twig', [
            "users" => $users,
            "ads" => $ads,
            "companies" => $companies
        ]);
    }

    /**
     * @Route("/database/user/create", name="database_user_create")
     */
    public function createUser(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute("database");
        }

        return $this->render('database/create_user.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/database/user/edit/{id}", name="database_user_edit")
     */
    public function editUser(EntityManagerInterface $em, Request $request, $id): Response
    {
        $user = $em->getRepository(User::class)->findOneById($id);

        if (!$user)
            return $this->redirectToRoute("database");
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("database");
        }

        return $this->render('database/edit_user.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="database_user_delete")
     */
    public function deleteUser(EntityManagerInterface $em, $id): Response
    {
        $user = $em->getRepository(User::class)->findOneById($id);

        if ($user) {
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute("database");
    }

    /**
     * @Route("/database/company/create", name="database_company_create")
     */
    public function createCompany(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(CompanyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();

            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute("database");
        }

        return $this->render('database/create_company.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/database/company/edit/{id}", name="database_company_edit")
     */
    public function editCompany(EntityManagerInterface $em, Request $request, $id): Response
    {
        $company = $em->getRepository(Company::class)->findOneById($id);

        if (!$company)
            return $this->redirectToRoute("database");
        
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = $form->getData();

            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute("database");
        }

        return $this->render('database/edit_company.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/company/delete/{id}", name="database_company_delete")
     */
    public function deleteCompany(EntityManagerInterface $em, $id): Response
    {
        $company = $em->getRepository(Company::class)->findOneById($id);

        if ($company) {
            $em->remove($company);
            $em->flush();
        }
        return $this->redirectToRoute("database");
    }

    /**
     * @Route("/database/ad/create", name="database_ad_create")
     */
    public function createAd(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AdType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $form->getData();
            $ad->setCreator($user);

            $em->persist($ad);
            $em->flush();
            return $this->redirectToRoute("database");
        }

        return $this->render('database/create_ad.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/database/ad/edit/{id}", name="database_ad_edit")
     */
    public function editAd(EntityManagerInterface $em, Request $request, $id): Response
    {
        $ad = $em->getRepository(Ad::class)->findOneById($id);

        if (!$ad)
            return $this->redirectToRoute("database");
        
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $form->getData();

            $em->persist($ad);
            $em->flush();
            return $this->redirectToRoute("database");
        }

        return $this->render('database/edit_ad.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/ad/delete/{id}", name="database_ad_delete")
     */
    public function deleteAd(EntityManagerInterface $em, $id): Response
    {
        $ad = $em->getRepository(Ad::class)->findOneById($id);

        if ($ad) {
            $em->remove($ad);
            $em->flush();
        }
        return $this->redirectToRoute("database");
    }
}
