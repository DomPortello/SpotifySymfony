<?php

namespace App\Form;

use App\Entity\Genre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Genre',
                'attr' => [
                    'placeholder' => 'Genre'
                ]
            ])
            ->add('smallPicture', TextType::class, [
                'label' => 'Url petite image',
                'attr' => [
                    'placeholder' => 'Url petite image'
                ]
            ])
            ->add('mediumPicture', TextType::class, [
                'label' => 'Url image moyenne',
                'attr' => [
                    'placeholder' => 'Url image moyenne'
                ]
            ])
            ->add('bigPicture', TextType::class, [
                'label' => 'Url grande image',
                'attr' => [
                    'placeholder' => 'Url grande image'
                ]
            ])
            ->add('xlPicture', TextType::class, [
                'label' => 'Url image XL',
                'attr' => [
                    'placeholder' => 'Url image XL'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
        ]);
    }
}
