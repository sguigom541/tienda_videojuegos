<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistroLoginClienteController extends AbstractController
{
    /**
     * @Route("/registro/login/cliente", name="registro_login_cliente")
     */
    public function index(): Response
    {
        return $this->render('registro_login_cliente/index.html.twig', [
            'controller_name' => 'RegistroLoginClienteController',
        ]);
    }
}
