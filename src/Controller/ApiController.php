<?php

namespace App\Controller;

use App\Entity\DetallePedido;
use App\Entity\Pedido;
use App\Entity\Usuario;
use App\Entity\Videojuego;
use DateTime;
use App\Repository\CategoriaRepository;
use App\Repository\PlataformaRepository;
use App\Repository\SliderRepository;
use App\Repository\UsuarioRepository;
use App\Repository\VideojuegoRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Funciones;
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
     * @param $plataforma
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
     * @Route("/verVideojuego", name="app_VerVideojuego_frontend")
     */
    public function verVideojuego(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $videojuego=$em->getRepository(Videojuego::class)->find($request->get("id"));
        $url='./uploads/videojuegos/'.$videojuego->getNombre().'/imagenes';
        $fotosVideojuegos=scandir($url);
        $arrayFotos=array();

        foreach($fotosVideojuegos as $clave=>$valor)
        {
            array_push($arrayFotos,$url."/".$valor);
        }
        return $this->render('frontend/showProducto.html.twig', [
            'videojuego' => $videojuego,
            'fotosProducto' => $arrayFotos,
        ]);
    }

    /**
     * @Route("/slider")
     * @param SliderRepository $sliderRepository
     * @return Response
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
            $usuario=$em->getRepository(Usuario::class)->findOneBy(['email'=>$request->get("email")]);
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

    /*************************************************************************
     *
     * API QUE CONTROLA  LA ADMINISTRACION DE LA CUENTA DE USUARIO LOGUEADO
     *
     ************************************************************************/

    /**
     * @Route("/datosPersonales",name="app_datospersonales_frontend")
     * @param Request $request
     * @param UsuarioRepository $usuarioRepository
     * @return Response
     */
    public function misDatos(Request $request,UsuarioRepository $usuarioRepository): Response
    {
        $usuario="";

        $usuario=$usuarioRepository->find($this->getUser()->getId());
        $response = new Response();
        /*Con esto digo que lo que re voy a pasar es un json, para luego hacer el parse lo hace solo*/
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($usuario));

        return $response;
    }



    /**
     * @Route("/envioCorreo", name="app_envioCorreo_frontend")
     */
    public function enviarCorreo(UsuarioRepository $usuario)
    {
        $enviado=false;

        $usuario= $usuario->findBy(["id"=>$this->getUser()->getId()]);

        if($usuario[0] !=null)
        {
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $usuario[0],
                (new TemplatedEmail())
                    ->from(new Address('videojuegosjaen@gmail.com', 'Videojuegos Jaén'))
                    ->to($usuario[0]->getEmail())
                    ->subject('Por favor confirma tu cuenta de Videojuegos Jaén')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $enviado = true;
        }

        $response = new Response();
        
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($enviado));
        return $response;
    }





    /*************************************************************************
     * 
     * API QUE CONTROLA EL MENU DE LA PAGINA WEB
     * 
     ************************************************************************/



    /**
     * @Route("/miCuenta", name="app_miCuenta_frontend")
     */
    public function miCuenta(): Response
    {

        return $this->render('frontend/micuenta.html.twig');
    }


    /**
     * @Route("/inicio",name="app_inicio_frontend")
     */
    public function inicio(): Response
    {
        return $this->render('frontend/inicio.html.twig');
    }

    /**
     * @Route("/carrito", name="app_verCarrito_frontend")
     */
    public function verCarrito(): Response
    {
        return $this->render('frontend/carrito.html.twig');
    }




    /**
     * @Route("/contacto",name="app_contacto")
     * @return Response
     */
    public function contacto() :Response
    {
        return $this->render('frontend/contacto.html.twig');
    }
    

    /**
     * @Route("/catalogo", name="app_catalogo_frontend")
     */
    public function cargaCatalogo(): Response
    {
        return $this->render('frontend/catalogoVideojuegos.html.twig');
    }



    /**
     * @Route("/registrologin",name="app_registrologin_frontend")
     */
    public function formuRegistroLogin() :Response
    {
        return $this->render('frontend/registrocliente.html.twig');
    }

    /*************************************************************************
     * 
     * CARRITO DE LA PAGINA WEB
     * 
     ************************************************************************/


     /**
     * @Route("/addCarrito", name="app_addcarrito_frontend")
     */
    public function addCarrito(Request $request): Response
    {
        $usuario=$this->getUser();
        $carrito=$usuario->getCarrito();

        $encontrado = false;
        $indice = 0;

        //Miro si existe el producto y actualizo la cantidad

        if (!empty($carrito)) {
            for ($i = 0; $i < count($carrito); $i++) {
                if ($carrito[$i]["idVideojuego"] == $request->get("idVideojuego")) {
                    $indice = $i;
                    $encontrado = true;
                }
            }
        }

        if ($encontrado) {
            $carrito[$indice]["cantidadElegida"] = $carrito[$indice]["cantidadElegida"] + $request->get("cantidadElegida");
        } else {
            $carrito[] = [
                "idVideojuego" => $request->get("idVideojuego"),
                "cantidadElegida" => $request->get("cantidadElegida"),
            ];
        }

        $funciona = true;
        try {
            $em = $this->getDoctrine()->getManager();
            $usuario->setCarrito($carrito);
            $em->persist($usuario);
            $em->flush();
        } catch (\Throwable $th) {
            
            $funciona = false;
        }

        $response = new Response();
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($funciona));
        return $response;
    }

    /**
     * @Route("/showCarrito", name="app_showCarrito_frontend")
     */
    public function showCarrito(): Response
    {
        $usuario = $this->getUser();
        $carrito = $usuario->getCarrito();

        $arrayCarrito = [];
        $em = $this->getDoctrine()->getManager();

        foreach ($carrito as $producto)
        {

            $videojuego=$em->getRepository(Videojuego::class)->find($producto["idVideojuego"]);
            $url='./uploads/videojuegos/'.$videojuego->getNombre().'/imagenPrincipal/';
            $arrayCarrito[]=[
                "id" => $videojuego->getId(),
                "nombre"=>$videojuego->getNombre(). " ". $videojuego->getPlataforma()->getNombre(),
                "foto"=>$url.$videojuego->getImgPrincipal(),
                "cantidadElegida"=>$producto["cantidadElegida"],
                "precio"=>$videojuego->getPrecio(),
                "descuento" => $videojuego->getDescuento(),
                "cantidad" => $videojuego->getStock(),
            ];

        }

        $response = new Response();
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($arrayCarrito));
        return $response;

    }

    /**
     * @Route("/updateCarrito", name="app_update_frontend")
     * @param Request $request
     * @return Response
     */
    public function updateCarrito(Request $request) : Response
    {
        $usuario = $this->getUser();
        $carrito = $usuario->getCarrito();

        //Miro si existe el producto para hacer un update de la cantidad
        if (!empty($carrito)) {
            for ($i = 0; $i < count($carrito); $i++) {
                if ($carrito[$i]["idVideojuego"] == $request->get("idVideojuego")) {
                    $carrito[$i]["cantidadElegida"] = $request->get("cantidadElegida");

                    break;
                }
            }
        }

        $funciona = true;
        try {
            $em = $this->getDoctrine()->getManager();
            $usuario->setCarrito($carrito);
            $em->persist($usuario);
            $em->flush();
        } catch (\Throwable $th) {
            //echo $th;
            $funciona = false;
        }

        $response = new Response();
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($funciona));
        return $response;
    }


    /**
     * @Route("/deleteCarrito", name="app_deleteCarrito_frontend")
     * @param Request $request
     * @return Response
     */

    public function deleteCarrito(Request $request) : Response
    {
        $usuario = $this->getUser();
        $carrito = $usuario->getCarrito();

        //Miro si existe el producto para hacer un update de la cantidad
        if (!empty($carrito)) {
            for ($i = 0; $i < count($carrito); $i++) {
                if ($carrito[$i]["idVideojuego"] == $request->get("idVideojuego")) {
                    unset($carrito[$i]);
                    $carrito = array_values($carrito);

                    break;
                }
            }
        }
        $funciona = true;
        try {
            $em = $this->getDoctrine()->getManager();
            $usuario->setCarrito($carrito);
            $em->persist($usuario);
            $em->flush();
        } catch (\Throwable $th) {
            //echo $th;
            $funciona = false;
        }

        $response = new Response();
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($funciona));
        return $response;
    }

    /**
     * @Route("/pagar", name="pagar_frontend")
     */
    public function pagar()
    {
        $usuario = $this->getUser();
        $carrito = $usuario->getCarrito();

        $correcto = true;

        try{
            $pedido = new Pedido();
            $hoy = new DateTime();
            $funciones=new Funciones();

            $em = $this->getDoctrine()->getManager();
            $totalFactura = 0;

            foreach($carrito as $videojuego)
            {
                $detallePedido= new DetallePedido();


                $videojuego=$em->getRepository(Videojuego::class)->find($videojuego["idVideojuego"]);

                $detallePedido->setCantidadCompra($videojuego["cantidadElegida"]);
                $detallePedido->setVideojuego($videojuego);
                $detallePedido->setPrecioVideojuego($videojuego->getPrecio());
                $detallePedido->setDescuento($videojuego->getDescuento());

                $totalFactura=$totalFactura+$funciones->calculoPrecio(
                    $videojuego->getDescuento(),
                    $videojuego->getPrecio(),
                    $videojuego["cantidadElegida"]
                );
                $detallePedido->setPedido($pedido);
                $em->persist($detallePedido);
                $stock=$videojuego->getStock()-$videojuego["cantidadElegida"];
                $videojuego->setStock($stock);
                $em->persist($videojuego);

            }
            $pedido->setTotalCompra(number_format($totalFactura, 2, '.', ''));
            $pedido->setFechaPedido($hoy);
            $pedido->setUsuario($usuario);

            $usuario->setCarrito([]);
            $em->persist($usuario);
            $em->flush();
        }catch(\Throwable $th)
        {
            $correcto = false;
        }

        $response = new Response();
        /*Con esto digo que lo que re voy a pasar es un json, para luego hacer el parse lo hace solo*/
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode($correcto));
        return $response;
    }

    /**
     * @Route("/cuentaCarrito", name="cCarrito_frontend")
     */
    public function cuentaCarrito(): Response
    {
        $usuario = $this->getUser()->getCarrito();

        $response = new Response();
        /*Con esto digo que lo que re voy a pasar es un json, para luego hacer el parse lo hace solo*/
        $response->headers->set("Content-Type", "application/json");
        $response->setContent(json_encode(count($usuario)));
        return $response;
    }
}
