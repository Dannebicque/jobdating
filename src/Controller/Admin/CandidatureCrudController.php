<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

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
            DateTimeField::new('creneau')->setFormat('none','HH:mm'),
            AssociationField::new('etudiant', 'Etudiant')->setCrudController(EtudiantCrudController::class),

        ];
    }

}
