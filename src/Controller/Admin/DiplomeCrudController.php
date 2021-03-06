<?php

namespace App\Controller\Admin;

use App\Entity\Diplome;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DiplomeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Diplome::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des diplômes')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('libelle', 'Libellé'),
            TextField::new('sigle', 'Sigle'),
            TextField::new('color', 'Couleur'),
        ];
    }

}
