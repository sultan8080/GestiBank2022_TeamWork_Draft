<?php

namespace App\Controller;

use doctrine;
use App\Entity\Demande;

use App\Entity\User;

use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\BankService;

class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'app_dashboardAdmin')]
    public function indexAdmin(ManagerRegistry $doctrine): Response
    {
        //Liste message
        $message = $doctrine->getRepository(Message::class)->findAll();
        $demande = $doctrine->getRepository(Demande::class)->findAll();

        return $this->render('dashboard/Admin/index_admin.html.twig', [
            'controller_name' => 'DashboardController',
            'ListeMessage' => $message,
            'ListeDemande' => $demande,
        ]);
    }
    #[Route('/client', name: 'app_dashboardClient')]
    public function indexClient(): Response
    {
        return $this->render('dashboard/client/index.html.twig', [
            'controller_name' => 'DashboardController',
        
        ]);
    }

    #[Route('/client/operations', name: 'app_dashboardClient_operations')]
    public function indexClientOperations(): Response
    {
        return $this->render('dashboard/client/listeOperations.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }
    // still not working

    #[Route('/client/listeDemande', name: 'app_dashboardClient_listDemandes')]
    public function indexClientListDemandes(): Response
    {
        return $this->render('dashboard/client/listeDemandeClient.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/client/listeMessage', name: 'app_dashboardClient_listMessage')]
    public function indexClientListMessage(): Response
    {
        return $this->render('dashboard/client/listeMessageClient.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/client/newMessage', name: 'app_dashboardClient_newMessage')]
    public function indexClientNewMessage(): Response
    {
        return $this->render('dashboard/client/newMessage.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }
    
    #[Route('/client/notifications', name: 'app_dashboardClientNotifications')]
    public function indexClientNotifications(): Response
    {
        return $this->render('dashboard/client/notificationsClient.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/client/NouvelleDemande', name: 'app_dashboardClienNewDemande')]
    public function indexClientNewDemande(): Response
    {
        return $this->render('dashboard/client/newDemandeCompte.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/client/NewTransaction', name: 'app_dashboardClienNewTransaction')]
    public function indexClientNewTransaction(): Response
    {
        return $this->render('dashboard/client/newTransaction.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }

    

    #[Route('/client/profile', name: 'app_dashboardClientProfile')]
    public function indexClientNewProfile(): Response
    {
        return $this->render('dashboard/client/monProfile.html.twig', [
            // 'controller_name' => 'DashboardController',
        ]);
    }





    #[Route('/conseiller', name: 'app_dashboardConseiller')]
    public function indexConseiller(): Response
    {
        return $this->render('dashboard/indexConseiller.html.twig', [
            'controller_name' => 'DashboardController',
        
        ]);
    }
    #[Route('/guest', name: 'app_guest')]
    public function indexGuest(ManagerRegistry $doctrine): Response
    { 
        $bankService = $doctrine->getRepository(BankService::class)->findAll();
        return $this->render('dashboard/guest/indexGuest.html.twig', [
            'controller_name' => 'DashboardController',
            'ListeBankService' => $bankService,
        
        ]);
    }
}
