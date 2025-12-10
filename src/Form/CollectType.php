<?php

namespace App\Form;

use App\Entity\Editeur;
use App\Entity\Genre;
use App\Entity\Collect;
use App\Repository\GenreRepository;
use App\Entity\JeuVideo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Enum\StatutJeuEnum;

class CollectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jeuVideo', EntityType::class, [
                'class' => JeuVideo::class,
                'choice_label' => 'titre'
            ])
            ->add('statut', EnumType::class, [
                'class' => StatutJeuEnum::class,
                'choice_label' => fn(StatutJeuEnum $choice) => $choice->getLabel()
            ])
            ->add('prixAchat')
            ->add('dateAchat')
            ->add('commentaire')
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Collect::class,
        ]);
    }
}
