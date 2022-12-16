<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_dashboardClient_operations', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository): Response
    {
        return $this->render('dashboard/client/listeOperations.html.twig', [
            'transactions' => $transactionRepository->findAll(),
        ]);
    }

    #[Route('/NewTransaction', name: 'app_dashboardClienNewTransaction', methods: ['GET', 'POST'])]
    public function new(Request $request, CompteRepository $compteRepository, TransactionRepository $transactionRepository): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);
        // $comptes = $compteRepository->findBy($this->getUser()->getId());
        $comptes = $compteRepository->findBy(array('idUser' => $this->getUser()->getId()));

        if ($form->isSubmitted() && $form->isValid()) {
            // $transaction->setCreatedAt(new \DateTime());
            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_dashboardClient_operations', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/client/newTransaction.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
            'comptes' => $comptes,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionRepository->save($transaction, true);

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $transactionRepository->remove($transaction, true);
        }

        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
