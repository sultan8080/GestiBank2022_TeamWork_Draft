<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserNewType;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/listeConseiller', name: 'app_conseillerListe', methods: ['GET'])]
    public function indexListeConseiller(UserRepository $userRepository): Response
    {

        return $this->render('user/conseiller/indexConseiller.html.twig', [
            'users' => $userRepository->findAll(),

            // 'users' => $userRepository->findBy(array('roles' => ["ROLE_CONSEILLER","ROLE_USER"]),array('id' =>'ASC')),
        

        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new (Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/newConseiller', name: 'app_conseiller_new', methods: ['GET', 'POST'])]
    public function newConseiller(UserPasswordHasherInterface $userPasswordHasher, Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserNewType::class, $user);
        $form->handleRequest($request);
        $user->setRoles(array('ROLE_CONSEILLER'));
        

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_conseillerListe', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/conseiller/newConseiller.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/conseiller/{id}', name: 'app_Conseiller_show', methods: ['GET'])]
    public function showConseiller(User $user): Response
    {
        return $this->render('user/conseiller/showConseiller.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
    #[Route('/editConseiller/{id}', name: 'app_Conseiller_edit', methods: ['GET', 'POST'])]
    public function editConseiller(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        echo "ok 1";
        // dd($request);
        // $user = $userRepository->find($id);
        // $test = $request->getData('ROLES');

        // dd($user);
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user->setRoles($form->getData('ROLES'));
            echo "ok 2";
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            // $userRepository->save($user, true);

            return $this->redirectToRoute('app_conseillerListe', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/conseiller/editConseiller.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_conseillerListe', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/deleteConseiller/{id}', name: 'app_Conseiller_delete', methods: ['POST','GET'])]
    public function deleteConseiller(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_conseillerListe', [], Response::HTTP_SEE_OTHER);
    }
}
