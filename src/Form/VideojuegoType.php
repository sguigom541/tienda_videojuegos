<?php

namespace App\Form;

use App\Entity\Categoria;
use App\Entity\Plataforma;
use App\Entity\Videojuego;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VideojuegoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('lanzamiento', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('fechaHoraEntrada', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('precio')
            ->add('descuento')
            ->add('stock')
            ->add('descripcion')
            ->add('imgPrincipal', FileType::class, [
                'label' => 'Imagen (JPG, PNG)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/webp',
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Inserte un archivo extension jpg/png/jpeg/wepb',
                    ])
                ],
            ])
            ->add('imagenes', FileType::class, [
                'label' => 'Imagenes (JPG, PNG)',
                'multiple' => true,
                'required' => false,

            ])
            ->add('categoria', EntityType::class, [
                // looks for choices from this entity
                'class' => Categoria::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'nombre',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('plataforma', EntityType::class, [
                // looks for choices from this entity
                'class' => Plataforma::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'nombre',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Videojuego::class,
        ]);
    }
}
