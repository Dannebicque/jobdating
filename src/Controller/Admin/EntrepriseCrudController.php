<?php

namespace App\Controller\Admin;

use App\Classes\Export;
use App\Entity\Entreprise;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EntrepriseCrudController extends AbstractCrudController
{
    public function __construct(private Export $export)
    {}

    public static function getEntityFqcn(): string
    {
        return Entreprise::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewInvoice = Action::new('viewInvoice', 'Exporter', 'fa fa-download')->createAsGlobalAction()
            ->linkToCrudAction('exportListe');

        return $actions
            // you can set permissions for built-in actions in the same way
            ->add(Crud::PAGE_INDEX, $viewInvoice)

            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ;
    }

    public function exportListe()
    {
        return $this->export->exportEntreprise();
    }
}
