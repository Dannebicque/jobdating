<?php

namespace App\Controller\Admin;

use App\Classes\Export;
use App\Entity\Entreprise;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EntrepriseCrudController extends AbstractCrudController
{
    public function __construct(private Export $export)
    {
    }

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
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex(),
            TextField::new('raison_sociale', 'Raison Sociale'),
            TextField::new('adresse', 'Adresse'),
            TextField::new('code_postal', 'Code Postal'),
            TextField::new('ville', 'Ville'),
            TextField::new('salle', 'Salle'),
            BooleanField::new('participe', 'Participe'),
            IntegerField::new('nbStands', 'Nb Stands'),
            AssociationField::new('offres', 'Nb Offres')->setCrudController(OffreCrudController::class),
        ];
    }

    public function exportListe()
    {
        return $this->export->exportEntreprise();
    }
}
