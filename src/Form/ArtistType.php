<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'artiste",
                'attr' => [
                    'placeholder' => "Nom de l'artiste"
                ]
            ])
            ->add('smallPicture', TextType::class, [
                'label' => 'Picture small',
                'required' => false
            ])
            ->add('mediumPicture', TextType::class, [
                'label' => 'Picture medium',
                'required' => false
            ])
            ->add('bigPicture', TextType::class, [
                'label' => 'Picture big',
                'required' => false
            ])
            ->add('xlPicture', TextType::class, [
                'label' => 'Picture xl',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
