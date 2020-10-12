<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Spam;
use App\Form\SpamType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SpamController extends AbstractController
{
    /**
     * @Route("/spam" , name="spam")
     */
    public function spam(Request $request): Response
    {
        $spam = new spam();
        $form = $this->createForm(spamType::class, $spam);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($spam);
            $entityManager->flush();
        }
        $list = $this->getDoctrine()
            ->getRepository(spam::class)
            ->findAll();
        return $this->render('acceuil/spam.html.twig', [
            //'controller_name' => 'AcceuilController',
            'form' => $form->createView(),
            'list' => $list,
        ]);
    }
    /**
     * @Route("/spam/{id}")
     */
    public function supprimer($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $spam = $this->getDoctrine()
            ->getRepository(spam::class)
            ->find($id);
        $entityManager->remove($spam);
        $entityManager->flush($spam);
        $this->addFlash('info', 'Spam Supprimé !');
        return new RedirectResponse('/spam');
    }
}
