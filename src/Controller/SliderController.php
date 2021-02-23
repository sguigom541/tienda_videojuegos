<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/slider")
 */
class SliderController extends AbstractController
{
    /**
     * @Route("/", name="app_slider_index", methods={"GET"})
     * @param SliderRepository $sliderRepository
     * @return Response
     */
    public function index(SliderRepository $sliderRepository): Response
    {
        return $this->render('backend/slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_slider_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slider);
            $entityManager->flush();

            return $this->redirectToRoute('app_slider_index');
        }

        return $this->render('backend/slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_slider_show", methods={"GET"})
     * @param Slider $slider
     * @return Response
     */
    public function show(Slider $slider): Response
    {
        return $this->render('backend/slider/show.html.twig', [
            'slider' => $slider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_slider_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Slider $slider
     * @return Response
     */
    public function edit(Request $request, Slider $slider): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_slider_index');
        }

        return $this->render('backend/slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_slider_delete", methods={"DELETE"})
     * @param Request $request
     * @param Slider $slider
     * @return Response
     */
    public function delete(Request $request, Slider $slider): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($slider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_slider_index');
    }
}
