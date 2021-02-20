<?php

namespace App\Controller;

use App\Entity\Plataforma;
use App\Form\PlataformaType;
use App\Repository\PlataformaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/plataforma")
 */
class PlataformaController extends AbstractController
{
    /**
     * @Route("/", name="app_plataforma_index", methods={"GET"})
     * @param PlataformaRepository $plataformaRepository
     * @return Response
     */
    public function index(Request $request,PaginatorInterface $paginator,PlataformaRepository $plataformaRepository): Response
    {
        //Se paginan los resultados de la consulta
        $paginacion=$paginator->paginate($plataformaRepository->findAll(),$request->query->getInt('page', 1),
            // Items per page
            5);
        return $this->render('backend/plataforma/index.html.twig', [
            'plataformas' => $paginacion,
        ]);
    }

    /**
     * @Route("/new", name="app_plataforma_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('app_plataforma_index');
        }

        return $this->render('backend/plataforma/new.html.twig', [
            'plataforma' => $plataforma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_plataforma_show", methods={"GET"})
     */
    public function show(Plataforma $plataforma): Response
    {
        return $this->render('backend/plataforma/show.html.twig', [
            'plataforma' => $plataforma,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_plataforma_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plataforma $plataforma): Response
    {
        $form = $this->createForm(PlataformaType::class, $plataforma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_plataforma_index');
        }

        return $this->render('backend/plataforma/edit.html.twig', [
            'plataforma' => $plataforma,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_plataforma_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plataforma $plataforma): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plataforma->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plataforma);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plataforma_index');
    }
}
