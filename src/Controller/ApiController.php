<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\PlataformaRepository;
use App\Repository\SliderRepository;
use App\Repository\VideojuegoRepository;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier=$emailVerifier;
    }

    /**
     * @Route("/categoria")
     * @param CategoriaRepository $categoriaRepository
     * @return Response
     */
    public function categorias( CategoriaRepository $categoriaRepository): Response
    {
       $categorias=$categoriaRepository->findAll();
        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($categorias));
       return $response;
    }

    /**
     * @Route("/plataformas")
     * @param PlataformaRepository $plataformaRepository
     * @return Response
     */
    public function plataformas(PlataformaRepository $plataformaRepository) :Response
    {
        $plataformas=$plataformaRepository->findAll();
        $response = new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($plataformas));
        return $response;
    }

    /**
     * @Route("/videojuegos")
     * @param VideojuegoRepository $videojuegoRepository
     * @return Response
     */
    public function videojuegos(VideojuegoRepository $videojuegoRepository): Response
    {
        $videojuegos=$videojuegoRepository->findAll();
        $response=new Response();

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($videojuegos));
        return $response;
    }

    /**
     * @Route("/slider")
     */
    public function sliderPrincipal(SliderRepository $sliderRepository): Response
    {
        $slider=$sliderRepository->findAll();
        $response=new Response();
        
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($slider));
        return $response;
    }
}