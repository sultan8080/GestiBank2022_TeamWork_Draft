<?php

namespace App\Controller;

use doctrine;
use App\Entity\Demande;
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
        return $this->render('dashboard/indexClient.html.twig', [
            'controller_name' => 'DashboardController',
        
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
