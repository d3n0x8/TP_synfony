<?php

namespace App\Form;

use App\Entity\Editeur;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Entity\JeuVideo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JeuVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('developpeur')
            ->add('dateSortie')
            ->add('prix')
            ->add('description')
            ->add('imageUrl')
            ->add('editeur', EntityType::class, [
                'class' => Editeur::class,
                'choice_label' => 'nom',
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'nom',
                'query_builder' => function (GenreRepository $gr) {
                    return $gr->createQueryBuilder('g')
                        ->andWhere('g.actif = :actif')
                        ->setParameter('actif', true)
                        ->orderBy('g.nom', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JeuVideo::class,
        ]);
    }
}
