<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cvPdfFile', VichFileType::class, [
                'attr' => ['accept' => '.pdf,application/pdf'],
                'label' => 'Votre CV (obligatoire)',
                'required' => true,
                'allow_delete' => true,
                'delete_label' => 'Supprimer le fichier déjà présent',
                'download_uri' => true,
                'download_label' => 'Un fichier est présent. Voir le fichier',
                'asset_helper' => true,
                'help' => 'Vous devez joindre un fichier PDF pour présenter votre CV. Taille maximale autorisée : 5 Mo',
            ])
            ->add('lettrePdfFile', VichFileType::class, [
                'attr' => ['accept' => '.pdf,application/pdf'],
                'label' => 'Votre lettre de motivation',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer le fichier déjà présent',
                'download_uri' => true,
                'download_label' => 'Un fichier est présent. Voir le fichier',
                'asset_helper' => true,
                'help' => 'Vous pouvez joindre un fichier PDF pour présenter votre lettre de motivation/candidature. Taille maximale autorisée : 5 Mo',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
