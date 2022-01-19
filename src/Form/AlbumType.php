<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                'widget' => 'single_text',
            ])
            ->add('available', ChoiceType::class, [
                'label' => 'Disponible',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'oui' => true,
                    'non' => false
                ]
            ])
            ->add('lyrics', TextareaType::class, [
                'label' => 'Paroles',
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix'
            ])
            ->add('genre', CollectionType::class, [
                'label' => 'Genres',
                'label_attr' => [
                  'class' => 'd-inline w-auto'
                ],
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Genre::class,
                    'label' => false,
                    'choice_label' => 'name',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.name', 'ASC');
                    },
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'data-list-id' => 'genre'
                ]
            ])
            ->add('artist', EntityType::class, [
                'label' => 'Artiste',
                'class' => Artist::class,
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
