<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('representants', CollectionType::class, [
                'label' => null,
                'help' => 'Le contact principal de l\'entreprise pour le jobDating.',
                'entry_type' => RepresentantType::class,
                'allow_add' => false,
            ])
            ->add('raison_sociale', TextType::class)
            ->add('adresse', TextType::class)
            ->add('code_postal', TextType::class, ['attr' => ['maxlength' => 5]])
            ->add('ville', TextType::class)
            ->add('nbStands', IntegerType::class, [
                'attr' => ['min' => 0, 'max' => 5],
                'label' => 'Nombre de stands souhaité',
                'help' => 'Si vous souhaitez mener plusieurs entretiens en parallèle, vous pouvez indiquer le nombre de stands souhaité.'
            ])
            ->add('participe', ChoiceType::class, [
                'choices' => ['Oui' => true, 'Non' => false],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Vous participez au JobDating et serez présent le 2 juin ?',
            ])
            ->add('heureDebut', TimeType::class, [
                'hours' => range(14, 18),
                'minutes' => [00, 15, 30, 45],
                'label' => 'Heure de début',
                'help' => 'Le JobDating est prévu de 14h00 à 18h00, avec des rendez-vous toutes les 30 minutes. Précisez si vous ne pouvez pas être présent à partir de 14h.'
            ])
            ->add('heureFin', TimeType::class, [
                'hours' => range(14, 18),
                'minutes' => [00, 15, 30, 45],
                'label' => 'Heure de fin',
                'help' => 'Le JobDating est prévu de 14h00 à 18h00, avec des rendez-vous toutes les 30 minutes. Précisez si vous ne pouvez pas être présent jusque 18h.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
