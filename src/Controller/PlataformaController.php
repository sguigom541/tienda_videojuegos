<?php

namespace App\Controller;

use App\Entity\Plataforma;
use App\Form\PlataformaType;
use App\Repository\PlataformaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/plataforma")
 */
class PlataformaController extends AbstractController
{
    /**
     * @Route("/", name="plataforma_index", methods={"GET"})
     */
    public function index(PlataformaRepository $plataformaRepository): Response
    {
        return $this->render('plataforma/index.html.twig', [
            'plataformas' => $plataformaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="plataforma_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $plataforma = new Plataforma();
        $form = $this->createForm(PlataformaType::class, $plataforma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plataforma);
            $entityManager->flush();

            return $this->redirectToRoute('plataforma_index');
        }

        return $this->render('plataforma/new.html.twig', [
            'plataforma' => $plataforma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="plataforma_show", methods={"GET"})
     */
    public function show(Plataforma $plataforma): Response
    {
        return $this->render('plataforma/show.html.twig', [
            'plataforma' => $plataforma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="plataforma_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plataforma $plataforma): Response
    {
        $form = $this->createForm(PlataformaType::class, $plataforma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plataforma_index');
        }

        return $this->render('plataforma/edit.html.twig', [
            'plataforma' => $plataforma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="plataforma_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plataforma $plataforma): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plataforma->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plataforma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('plataforma_index');
    }
}
