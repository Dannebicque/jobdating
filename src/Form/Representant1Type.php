<?php

namespace App\Form;

use App\Entity\Constante;
use App\Entity\Representant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Representant1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'Monsieur' => Constante::MONSIEUR,
                    'Madame' => Constante::MADAME,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Civilité',
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ]);

        if ($options['edit'] === false) {
            $builder->
            add('sendPassword', ChoiceType::class, [
                'choices' => ['Oui' => true, 'Non' => false],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Envoyer un mot de passe ?',
                'help' => 'Cocher oui pour permettre un accès à l\'espace entreprise'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Representant::class,
            'edit' => false,
        ]);
    }
}
