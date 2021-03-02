<?php

namespace App\Controller;

use App\Entity\Videojuego;
use App\Form\VideojuegoType;
use App\Repository\VideojuegoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/videojuego")
 */
class VideojuegoController extends AbstractController
{
    /**
     * @Route("/", name="app_videojuego_index", methods={"GET"})
     * @param VideojuegoRepository $videojuegoRepository
     * @return Response
     */
    public function index(Request $request,PaginatorInterface $paginator,VideojuegoRepository $videojuegoRepository): Response
    {
        //Se paginan los resultados de la consulta
        $paginacion=$paginator->paginate($videojuegoRepository->findAll(),$request->query->getInt('page', 1),
            // Items per page
            5);
        return $this->render('backend/videojuego/index.html.twig', [
            'videojuegos' => $paginacion,
        ]);
    }

    /**
     * @Route("/new", name="app_videojuego_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $videojuego = new Videojuego();
        $form = $this->createForm(VideojuegoType::class, $videojuego);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $nombre=str_replace(' ','',$form->get('nombre')->getData());
            $videojuego->setNombre($nombre);
            $imagenPrincipal = $form->get('imgPrincipal')->getData();
            $imagenes=$form->get('imagenes')->getData();

            if ($imagenPrincipal)
            {
                //$fecha = new Date();
                $fecha=date('Y-m-d');
                try {

                    //$fotoFormateada = $fecha . '-' . trim($videojuego->getNombre(),"") . '-' . trim($videojuego->getPlataforma()->getNombre(),"") . '.' . $imagenPrincipal->guessExtension();
                    $fotoFormateada=$fecha.'-'.str_replace(' ','',$videojuego->getNombre()).'-'.str_replace(' ','',$videojuego->getPlataforma()->getNombre()).'.' . $imagenPrincipal->guessExtension();
                    $url=$this->getParameter('videojuego_directory').'/'.strtoupper(str_replace(' ','',$videojuego->getNombre())).'/'.'imagenPrincipal'.'/';
                    $imagenPrincipal->move($url, $fotoFormateada);

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $videojuego->setImgPrincipal($fotoFormateada);
            }
            //si existe el array
            if($imagenes)
            {
                //$fecha = new Date();
                $fecha=date('Y-m-d');
                $arrayImagenes=array();
                $url=$this->getParameter('videojuego_directory').'/'.str_replace(' ','',$videojuego->getNombre()).'/'.'imagenes'.'/';
                for ($i=0;$i<count($imagenes);$i++)
                {
                    //$imagenFormateada=$i.'-'.$fecha . '-' . trim($videojuego->getNombre(),"") . '-' . trim($videojuego->getPlataforma()->getNombre(),"") . '.' . $imagenes[$i]->guessExtension();
                    $imagenFormateada=$i.'-'.$fecha.'-'.str_replace(' ','',$videojuego->getNombre()).'-'.str_replace(' ','',$videojuego->getPlataforma()->getNombre()). '.' . $imagenes[$i]->guessExtension();
                    $imagenes[$i]->move($url,$imagenFormateada);
                    array_push($arrayImagenes,$imagenFormateada);


                }

                $videojuego->setImagenes($arrayImagenes);
            }




            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($videojuego);
            $entityManager->flush();

            return $this->redirectToRoute('app_videojuego_index');
        }

        return $this->render('backend/videojuego/new.html.twig', [
            'videojuego' => $videojuego,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_videojuego_show", methods={"GET"})
     */
    public function show(Videojuego $videojuego): Response
    {
        return $this->render('backend/videojuego/show.html.twig', [
            'videojuego' => $videojuego,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_videojuego_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Videojuego $videojuego
     * @return Response
     */
    public function edit(Request $request, Videojuego $videojuego): Response
    {
        $form = $this->createForm(VideojuegoType::class, $videojuego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($request->get('imagenes')==""){
                $videojuego->setImagenes($videojuego->getImagenes());
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_videojuego_index');
        }

        return $this->render('backend/videojuego/edit.html.twig', [
            'videojuego' => $videojuego,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_videojuego_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Videojuego $videojuego): Response
    {
        if ($this->isCsrfTokenValid('delete' . $videojuego->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($videojuego);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_videojuego_index');
    }


}
