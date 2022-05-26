<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CandidatureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidature::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex(),
            AssociationField::new('offre', 'Offre')->setCrudController(OffreCrudController::class),
            DateTimeField::new('creneau')->setFormat('none', 'HH:mm'),
            ChoiceField::new('hasCV')->renderAsBadges(
                [
                    'CV Déposé' => 'success',
                    'Pas de CV' => 'warning',
                ]
            )->setChoices(
                [
                    'CV Déposé' => 'CV Déposé',
                    'Pas de CV' => 'Pas de CV',
                ]
            ),
            ChoiceField::new('hasLettre')->renderAsBadges(
                [
                    'Lettre Déposée' => 'success',
                    'Pas de Lettre' => 'warning',
                ]
            )->setChoices(
                [
                    'Lettre Déposée' => 'Lettre Déposée',
                    'Pas de Lettre' => 'Pas de Lettre',
                ]
            ),
            AssociationField::new('etudiant', 'Etudiant')->setCrudController(EtudiantCrudController::class),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {

        return $actions
            // you can set permissions for built-in actions in the same way
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

}
