<?php

namespace App\Controller;

use App\Entity\DetallePedido;
use App\Form\DetallePedidoType;
use App\Repository\DetallePedidoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/detalle/pedido")
 */
class DetallePedidoController extends AbstractController
{
    /**
     * @Route("/", name="detalle_pedido_index", methods={"GET"})
     */
    public function index(DetallePedidoRepository $detallePedidoRepository): Response
    {
        return $this->render('detalle_pedido/index.html.twig', [
            'detalle_pedidos' => $detallePedidoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="detalle_pedido_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $detallePedido = new DetallePedido();
        $form = $this->createForm(DetallePedidoType::class, $detallePedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($detallePedido);
            $entityManager->flush();

            return $this->redirectToRoute('detalle_pedido_index');
        }

        return $this->render('detalle_pedido/new.html.twig', [
            'detalle_pedido' => $detallePedido,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="detalle_pedido_show", methods={"GET"})
     */
    public function show(DetallePedido $detallePedido): Response
    {
        return $this->render('detalle_pedido/show.html.twig', [
            'detalle_pedido' => $detallePedido,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="detalle_pedido_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DetallePedido $detallePedido): Response
    {
        $form = $this->createForm(DetallePedidoType::class, $detallePedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('detalle_pedido_index');
        }

        return $this->render('detalle_pedido/edit.html.twig', [
            'detalle_pedido' => $detallePedido,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="detalle_pedido_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DetallePedido $detallePedido): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detallePedido->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($detallePedido);
            $entityManager->flush();
        }

        return $this->redirectToRoute('detalle_pedido_index');
    }
}
