<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ApplyFormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use App\Entity\User;
use App\Entity\Ad;

use App\Form\Database\UserType;
use App\Form\AdType;

class AdController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        $ads = $em->getRepository(Ad::class)->findAll();
        
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * @Route("/ad/create", name="ad_create")
     */
    public function create_ad(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $name = $user->getUsername();

        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $form->getData();
            $ad->setCreator($user);

            $em->persist($ad);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('ad/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/ad/edit/{id}", name="ad_edit")
     */
    public function editAd(EntityManagerInterface $em, Request $request, $id): Response
    {
        $ad = $em->getRepository(Ad::class)->findOneById($id);

        if (!$ad)
            return $this->redirectToRoute("home");
        
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $form->getData();

            $em->persist($ad);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('ad/edit_ad.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/ad/remove/{id}", name="ad_remove")
     */
    public function deleteAd(EntityManagerInterface $em, $id): Response
    {
        $ad = $em->getRepository(Ad::class)->findOneById($id);

        if ($ad) {
            $em->remove($ad);
            $em->flush();
        }
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/ad/apply/{id}", name="ad_apply")
     */
    public function apply_ad(Request $request, EntityManagerInterface $em, $id): Response
    {
        $user = $this->getUser();

        $ad = $em->getRepository(Ad::class)->findOneById($id);
        if ($ad) {
            $user->addApplication($ad);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/ad/learn_more/{id}", name="ad_learn_more")
     */
    public function learn_more(Request $request, EntityManagerInterface $em, $id): Response
    {
        if ($request->isXmlHttpRequest()) {
            $ad = $em->getRepository(Ad::class)->findOneById($id);
            if ($ad) {
                $data = [
                    "Company:" => $ad->getCompany()->getName(),
                    "Location:" => $ad->getLocation(),
                    "Salary (per hours):" => $ad->getSalary(),
                    "Contact:" => $ad->getCreator()->getEmail()
                ];
                return new JsonResponse($data);
            }
        }
        return new Response('This is not an Ajax request!!!', 400);
    }
}
