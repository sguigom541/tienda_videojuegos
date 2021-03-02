<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class IndexFrontendController extends AbstractController
{
    /**
     * @Route("/", name="app_index_frontend")
     */
    public function index(): Response
    {
        return $this->render('frontend/index.html.twig', [
            'controller_name' => 'IndexFrontendController',
        ]);
    }

    /**
     * @Route("/catalogo",name="app_catalogo_videojuegos")
     */
    public function catalogoVideojuegos(): Response
    {
        return $this->render('frontend/catalogoVideojuegos.html.twig');
    }

}
