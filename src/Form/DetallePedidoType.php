<?php

namespace App\Form;

use App\Entity\DetallePedido;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetallePedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidadCompra')
            ->add('total')
            ->add('precioVideojuego')
            ->add('pedido')
            ->add('videojuego')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetallePedido::class,
        ]);
    }
}
