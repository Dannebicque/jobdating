<?php

namespace App\Controller\Admin;

use App\Classes\Export;
use App\Entity\Representant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RepresentantCrudController extends AbstractCrudController
{
    public function __construct(private Export $export)
    {}

    public static function getEntityFqcn(): string
    {
        return Representant::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewInvoice = Action::new('viewInvoice', 'Exporter', 'fa fa-download')->createAsGlobalAction()
            ->linkToCrudAction('exportListe');

        return $actions
            ->add(Crud::PAGE_INDEX, $viewInvoice)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function exportListe()
    {
        return $this->export->exportRepresentant();
    }
}
