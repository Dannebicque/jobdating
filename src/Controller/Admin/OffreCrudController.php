<?php

namespace App\Controller\Admin;

use App\Classes\Export;
use App\Entity\Offre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OffreCrudController extends AbstractCrudController
{
    public function __construct(private Export $export)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Offre::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $viewInvoice = Action::new('viewInvoice', 'Exporter', 'fa fa-download')->createAsGlobalAction()
            ->linkToCrudAction('exportListe');
        $exportPlanningOffre = Action::new('exportPlanningOffre', 'Exporter Planning',
            'fa fa-download')->createAsGlobalAction()
            ->linkToCrudAction('exportPlanningOffre');

        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, $viewInvoice)
            ->add(Crud::PAGE_INDEX, $exportPlanningOffre)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('titre'),
            TextField::new('descriptif')->hideOnIndex(),
            AssociationField::new('entreprise', 'Entreprise')->setCrudController(EntrepriseCrudController::class),
//            AssociationField::new('diplomes', 'Diplômes')->setCrudController(DiplomeCrudController::class)->setFormTypeOption('choice_label', 'sigle')->hideOnIndex(),
            AssociationField::new('parcours',
                'Parcours')->setCrudController(ParcoursCrudController::class)->setFormTypeOption('choice_label',
                'sigle')->hideOnIndex(),
        ];
    }

    public function exportListe()
    {
        return $this->export->exportOffre();
    }

    public function exportPlanningOffre()
    {
        return $this->export->exportPlanningOffres();
    }

}
