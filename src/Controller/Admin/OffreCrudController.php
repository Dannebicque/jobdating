<?php

namespace App\Controller\Admin;

use App\Entity\Offre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OffreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offre::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $viewInvoice = Action::new('viewInvoice', 'Exporter', 'fa fa-download')->createAsGlobalAction()
        ->linkToCrudAction('exportListe');


//        // if the method is not defined in a CRUD controller, link to its route
//        $sendInvoice = Action::new('sendInvoice', 'Send invoice', 'fa fa-envelope')
//            // if the route needs parameters, you can define them:
//            // 1) using an array
//            ->linkToRoute('invoice_send', [
//                'send_at' => (new \DateTime('+ 10 minutes'))->format('YmdHis'),
//            ])
//
//            // 2) using a callable (useful if parameters depend on the entity instance)
//            // (the type-hint of the function argument is optional but useful)
//            ->linkToRoute('invoice_send', function (Order $order): array {
//                return [
//                    'uuid' => $order->getId(),
//                    'method' => $order->getUser()->getPreferredSendingMethod(),
//                ];
//            });
//
//        // this action points to the invoice on Stripe application
//        $viewStripeInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice')
//            ->linkToUrl(function (Order $entity) {
//                return 'https://www.stripe.com/invoice/'.$entity->getStripeReference();
//            });

        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, $viewInvoice)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('titre'),
            AssociationField::new('entreprise', 'Entreprise')->setCrudController(EntrepriseCrudController::class),
            AssociationField::new('diplomes', 'Diplômes')->setCrudController(DiplomeCrudController::class)->setFormTypeOption('choice_label', 'sigle'),
        ];
    }

    public function exportListe()
    {}

}
