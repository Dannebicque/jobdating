<?php

namespace App\Controller\Admin;

use App\Entity\Parcours;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ParcoursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parcours::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('libelle', 'Libellé'),
            TextField::new('sigle', 'Sigle'),
            AssociationField::new('diplome', 'Diplôme')->setCrudController(DiplomeCrudController::class)
        ];
    }

}
