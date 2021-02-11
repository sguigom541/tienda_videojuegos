<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexBackendController extends AbstractController
{
    /**
     * @Route("/index/backend", name="index_backend")
     */
    public function index(): Response
    {
        return $this->render('index_backend/index.html.twig', [
            'controller_name' => 'IndexBackendController',
        ]);
    }
}
