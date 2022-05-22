<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use App\Entity\Diplome;
use App\Entity\Entreprise;
use App\Entity\Etudiant;
use App\Entity\Offre;
use App\Entity\Parcours;
use App\Entity\Representant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('JobDating');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Entreprise', 'fas fa-building');
        yield MenuItem::linkToCrud('Entreprises', 'fas fa-briefcase', Entreprise::class);
        yield MenuItem::linkToCrud('Représentants', 'fas fa-user-tie', Representant::class);
        yield MenuItem::linkToCrud('Offres', 'fas fa-list', Offre::class);
        yield MenuItem::section('Etudiant', 'fas fa-graduation-cap');
        yield MenuItem::linkToCrud('Etudiants inscrits', 'fas fa-users', Etudiant::class);
        yield MenuItem::linkToCrud('Candidatures', 'fas fa-calendar', Candidature::class);
        yield MenuItem::section('Settings', 'fas fa-list')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Diplômes', 'fas fa-list', Diplome::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Parcours', 'fas fa-list', Parcours::class)->setPermission('ROLE_ADMIN');
    }
}
