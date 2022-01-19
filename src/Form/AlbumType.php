<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('smallCover', TextType::class, [
                'label' => 'Cover small'
            ])
            ->add('mediumCover', TextType::class, [
                'label' => 'Cover medium'
            ])
            ->add('bigCover', TextType::class, [
                'label' => 'Cover big'
            ])
            ->add('xlCover', TextType::class, [
                'label' => 'Cover xl'
            ])
            ->add('label', TextType::class, [
                'label' => 'Label'
            ])
            ->add('nbTracks', IntegerType::class, [
                'label' => 'Nombre de musique',
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée',
            ])
            ->add('releaseAt', DateType::class, [
                'label' => 'Sorti le',
                'widget' => 'single_text'
            ])
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
