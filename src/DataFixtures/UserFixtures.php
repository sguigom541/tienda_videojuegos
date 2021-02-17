<?php

namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $usuario=new Usuario();
        $usuario->setNombre('Francisco');
        $usuario->setApe1('Jimenez');
        $usuario->setApe2('Perez');
        $usuario->setEmail('admin@admin.com');
        $usuario->setDireccion('Calle Hermana Esperanza, 20, Jaén, España');
        $usuario->setPassword($this->userPasswordEncoder->encodePassword(
            $usuario,
            'admin'
        ));
        $usuario->setRoles(['ROLE_ADMIN']);
        $manager->persist($usuario);
        $manager->flush();

        $usuario = new Usuario();
        $usuario->setNombre('María');
        $usuario->setApe1('Perez');
        $usuario->setApe2('Gonzalez');
        $usuario->setDireccion('Calle Hermana Blanca, 20, Jaén, España');
        $usuario->setEmail('user@user.com');
        $usuario->setRoles(['ROLE_USER']);
        $usuario->setPassword($this->userPasswordEncoder->encodePassword(
            $usuario,
            'user'
        ));
        $manager->persist($usuario);
        $manager->flush();

    }
}
