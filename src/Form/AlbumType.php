<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [

            ])
            ->add('smallCover')
            ->add('mediumCover')
            ->add('bigCover')
            ->add('xlCover')
            ->add('label')
            ->add('nbTracks')
            ->add('duration')
            ->add('releaseAt')
//            ->add('available')
//            ->add('lyrics')
//            ->add('price')
//            ->add('genre')
//            ->add('artist')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
