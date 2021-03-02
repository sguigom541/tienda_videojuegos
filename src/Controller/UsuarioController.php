<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/usuario")
 */
class UsuarioController extends AbstractController
{
    /**
     * @Route("/", name="app_usuario_index", methods={"GET"})
     */
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('backend/usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_usuario_new", methods={"GET","POST"})
     */
    public function new(UserPasswordEncoderInterface $userPasswordEncoder,Request $request): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $form->get('password')->getData();
            $usuario->setPassword($userPasswordEncoder->encodePassword($usuario,$form->get('password')->getData()));

            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_usuario_index');
        }

        return $this->render('backend/usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_usuario_show", methods={"GET"})
     */
    public function show(Usuario $usuario): Response
    {
        return $this->render('backend/usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_usuario_edit", methods={"GET","POST"})
     */
    public function edit(UserPasswordEncoderInterface $userPasswordEncoder,Request $request, Usuario $usuario): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->get('password')->getData();
            $usuario->setPassword($userPasswordEncoder->encodePassword($usuario,$form->get('password')->getData()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_usuario_index');
        }

        return $this->render('backend/usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_usuario_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_usuario_index');
    }
}
