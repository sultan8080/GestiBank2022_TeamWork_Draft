<?php

namespace App\Controller;

use App\Entity\BankService;
use App\Form\BankServiceType;
use App\Repository\BankServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/bankservice')]
class BankServiceController extends AbstractController
{
    #[Route('/', name: 'app_bank_service_index', methods: ['GET'])]
    public function index(BankServiceRepository $bankServiceRepository): Response
    {
        return $this->render('bank_service/index.html.twig', [
            'bank_services' => $bankServiceRepository->findAll(),
        ]);
    }

    #[Route('/afficheService', name: 'app_bank_service_indexpublic', methods: ['GET'])]
    public function index2(BankServiceRepository $bankServiceRepository): Response
    {
        return $this->render('bank_service/serviceBank.html.twig', [
            'bank_services' => $bankServiceRepository->findAll(),
        ]);
    }
    
    
    
    // #[Route('/{id}', name: 'app_bank_service_indexpublic_detail', methods: ['GET'])]
    // public function index5(int $id, BankServiceRepository $bankServiceRepository): Response
    // {
    //     return $this->render('bank_service/serviceBankDetail.html.twig', [
    //         'bank_services' => $bankServiceRepository->find($id),
    //     ]);
    // }

    // #[Route('/afficheService/{id}', name: 'app_bank_service_indexpublic_detail', methods: ['GET'])]
    // public function index3(BankServiceRepository $bankServiceRepository): Response
    // {
    //     return $this->render('bank_service/serviceBankDetail.html.twig', [
    //         'bank_services' => $bankServiceRepository->findAll(),
    //     ]);
    // }
    

    
    #[Route('/new', name: 'app_bank_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BankServiceRepository $bankServiceRepository): Response
    {
        $bankService = new BankService();
        $form = $this->createForm(BankServiceType::class, $bankService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $logoLink = $form->get('logo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the logo must be processed only when a logo is uploaded
            if ($logoLink) {

                $originalLogoFilename = pathinfo($logoLink->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalLogoFilename.'-'.uniqid().'.'.$logoLink->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $logoLink->move(
                        $this->getParameter('logo_folder'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $bankService->setLogo($newFilename);
            }

            // ... persist the $product variable or any other work

            $bankServiceRepository->save($bankService, true);

            return $this->redirectToRoute('app_bank_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bank_service/new.html.twig', [
            'bank_service' => $bankService,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_bank_service_show', methods: ['GET'])]
    public function show(BankService $bankService): Response
    {
        return $this->render('bank_service/show.html.twig', [
            'bank_service' => $bankService,
        ]);
    }


    #[Route('/afficheService/{id}', name: 'bank_service_detail', methods: ['GET'])]
    public function showService(BankService $bankService): Response
    {
        return $this->render('bank_service/serviceBankDetail.html.twig', [
            'bank_service' => $bankService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bank_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BankService $bankService, BankServiceRepository $bankServiceRepository): Response
    {
        $form = $this->createForm(BankServiceType::class, $bankService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bankServiceRepository->save($bankService, true);

            return $this->redirectToRoute('app_bank_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bank_service/edit.html.twig', [
            'bank_service' => $bankService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bank_service_delete', methods: ['POST'])]
    public function delete(Request $request, BankService $bankService, BankServiceRepository $bankServiceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bankService->getId(), $request->request->get('_token'))) {
            $bankServiceRepository->remove($bankService, true);      
        }
        return $this->redirectToRoute('app_bank_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
