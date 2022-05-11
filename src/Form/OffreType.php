<?php

namespace App\Form;

use App\Entity\Diplome;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('descriptif', TextareaType::class, ['required' => false, 'label' => 'Description de l\'offre', 'help' => 'Vous pouvez indiquer un court texte pour faciliter la lecture des offres et joindre un PDF avec les détails', 'attr' => ['rows' => '10']])
            ->add('pdfFile', VichFileType::class, [
                'label' => 'Fichier PDF de l\'offre',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer le fichier déjà présent',
                'download_uri' => true,
                'download_label' => 'Un fichier est présent. Voir le fichier',
                'asset_helper' => true,
                'help' => 'Vous pouvez joindre un fichier PDF pour présenter les détails de l\'offre.',
            ])
//            ->add('entreprise')
            ->add('diplomes', EntityType::class, [
                'class' => Diplome::class,
                'choice_label' => 'display',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
