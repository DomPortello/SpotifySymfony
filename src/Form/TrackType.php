<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Track;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('duration')
            ->add('position')
            ->add('rank')
            ->add('releaseAt', DateType::class, [
                'label' => 'Sorti le',
                'widget' => 'single_text',
            ])
            ->add('lyrics')
            ->add('preview')
            ->add('price')
            ->add('album', EntityType::class, [
                'label' => 'Album',
                'class' => Album::class,
                'choice_label' => 'title',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                },
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
            'data_class' => Track::class,
        ]);
    }
}
