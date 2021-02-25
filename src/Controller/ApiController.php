<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\CategoriaRepository;
use App\Repository\PlataformaRepository;
use App\Repository\SliderRepository;
use App\Repository\VideojuegoRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

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

    /*************************************************************************
     ********** API QUE CONTROLA  EL CATALOGO DE LA PAGINA WEB***************
     ************************************************************************/

    /**
     * @Route("/categorias")
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
     * @Route("/videojuegos/cat/{categoria}", name="app_filtro_videojuegos_categoria")
     * @param VideojuegoRepository $videojuegoRepository
     * @param $categoria
     * @return Response
     */
    public function videojuegosCategoria (VideojuegoRepository $videojuegoRepository,$categoria):Response
    {
        $videojuegos=$videojuegoRepository->findBy(['categoria'=>$categoria]);
        $response = new Response();

        /*Con esto digo que lo que re voy a pasar es un json, para luego hacer el parse lo hace solo*/
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($videojuegos));
        return $response;
    }

    /**
     * @Route("/videojuegos/plat/{plataforma}", name="app_filtro_videojuegos_plataforma")
     * @param VideojuegoRepository $videojuegoRepository
     * @param $categoria
     * @return Response
     */
    public function videojuegosPlataforma (VideojuegoRepository $videojuegoRepository,$plataforma):Response
    {
        $videojuegos=$videojuegoRepository->findBy(['plataforma'=>$plataforma]);
        $response = new Response();

        /*Con esto digo que lo que re voy a pasar es un json, para luego hacer el parse lo hace solo*/
        $response->headers->set("Content-Type", "application/json");
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

    /*************************************************************************
     *
     * API PARA EL REGISTRO DE UN NUEVO USUARIO
     *
     ************************************************************************/
    /**
     * @Route("/registrocliente", name="app_registro_cliente")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param LoginFormAuthenticator $loginFormAuthenticator
     * @return Response
     */
    public function addUser(Request $request,UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator): Response
    {
        $correcto=true;
        try {
            $usuario=new Usuario();
            $usuario->setNombre($request->get('nombre'));
            $usuario->setApe1($request->get('ape1'));
            $usuario->setApe2($request->get('ape2'));
            $usuario->setEmail($request->get('email'));
            $usuario->setPassword($userPasswordEncoder->encodePassword($usuario, $request->get("password")));
            $usuario->setDireccion($request->get('direccion'));
            $usuario->setRoles(["ROLE_USER"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();

            //se genera el email de confirmación

            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $usuario,
                (new TemplatedEmail())
                    ->from(new Address('videojuegosjaen@gmail.com', 'VideojuegosJaen'))
                    ->to($usuario->getEmail())
                    ->subject('Por favor confirme tu cuenta de videojuegos jaén')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $correcto = $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $usuario,
                $request,
                $loginFormAuthenticator,
                'main' // firewall name in security.yaml
            );
        }catch(\Throwable $th)
        {
            $correcto = false;
        }
        $response = new Response();

        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($correcto));
        return $response;
    }

    /**
     * @Route("/updateUsuario", name="app_update_usuario")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     */
    public function updateUsuario(Request $request,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {

        $correcto = true;

        try {
            $em = $this->getDoctrine()->getManager();
            $usuario=$em->getRepository(Usuario::class)->findOneBy(['id'=>$request->get("id")]);
            $usuario->setNombre($request->get('nombre'));
            $usuario->setApe1($request->get('ape1'));
            $usuario->setApe2($request->get('ape2'));
            $usuario->setEmail($request->get('email'));
            $usuario->setDireccion($request->get('direccion'));
            if($request->get('password')!="")
            {
                $usuario->setPassword($userPasswordEncoder->encodePassword($usuario, $request->get("password")));
            }
            $em->flush();
        } catch (\Throwable $th) {
            $correcto = false;
        }

        $response = new Response();
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($correcto));
        return $response;
    }
}
