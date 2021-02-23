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
     * @Route("/registrocliente", name="app_registro_cliente")
     */
    public function registro(): Response
    {
        return $this->render('frontend/registrocliente.html.twig');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/cuenta", name="app_cuenta_cliente")
     */
    public function cuentaUsuario(): Response
    {
        $usuarioSesionIniciada=$this->getUser()->getId();
        return $this->render('frontend/miCuenta.html.twig',[
            'usuarioSesionIniciada'=>$usuarioSesionIniciada
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
