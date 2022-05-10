<?php

namespace App\Controller\Admin;

use App\Entity\Representant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RepresentantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Representant::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
