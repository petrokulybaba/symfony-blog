<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id<\d+>}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id)
    {
        $author = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('profile/view.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/profile/update/{id<\d+>}")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $author = $this->getDoctrine()->getRepository(User::class)->find($id);

        $form = $this->createForm(UpdateProfileType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            $this->addFlash('notice', 'Profile updated!');

            return $this->redirectToRoute('app_profile_view', [
                'id' => $author->getId()
            ]);
        }

        return $this->render('profile/update.html.twig', [
            'form' => $form->createView(),
            'author' => $author
        ]);
    }

    /**
     * @Route("/profile/{id<\d+>}/articles")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlesAction($id)
    {
        $author = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('profile/articles.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/profile/{id<\d+>}/likes")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function likesAction($id)
    {
        $author = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('profile/likes.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/profile/{id<\d+>}/comments")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentsAction($id)
    {
        $author = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('profile/comments.html.twig', [
            'author' => $author
        ]);
    }
}