<?php

namespace App\Controller;

use App\Entity\Actualites;
use App\Form\ActualitesType;
use App\Repository\ActualitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actualites')]
class ActualitesController extends AbstractController
{
    #[Route('/', name: 'app_actualites_index', methods: ['GET'])]
    public function index(ActualitesRepository $actualitesRepository): Response
    {
        return $this->render('actualites/index.html.twig', [
            'actualites' => $actualitesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_actualites_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActualitesRepository $actualitesRepository): Response
    {
        $actualite = new Actualites();
        $form = $this->createForm(ActualitesType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actualitesRepository->save($actualite, true);

            return $this->redirectToRoute('app_actualites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actualites/new.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_actualites_show', methods: ['GET'])]
    public function show(Actualites $actualite): Response
    {
        return $this->render('actualites/show.html.twig', [
            'actualite' => $actualite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_actualites_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Actualites $actualite, ActualitesRepository $actualitesRepository): Response
    {
        $form = $this->createForm(ActualitesType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actualitesRepository->save($actualite, true);

            return $this->redirectToRoute('app_actualites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('actualites/edit.html.twig', [
            'actualite' => $actualite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_actualites_delete', methods: ['POST'])]
    public function delete(Request $request, Actualites $actualite, ActualitesRepository $actualitesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->request->get('_token'))) {
            $actualitesRepository->remove($actualite, true);
        }

        return $this->redirectToRoute('app_actualites_index', [], Response::HTTP_SEE_OTHER);
    }
}
