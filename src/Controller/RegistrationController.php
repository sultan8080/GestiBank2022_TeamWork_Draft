<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(MailerInterface $mailer,Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
            ->from('gk@smart-it-partner.com')
            ->to($user->getEmail())
            ->subject('Validation Création de compte')
            ->html('<H2>Félicitation votre demande a été validé et votre compte est desormais actif</H2>');

            $mailer->send($email);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(array('ROLE_GUEST'));
            //$user->setRoles(array('ROLE_CLIENT'));
            // $user->setRoles(array('ROLE_CONSEILER'));
            // $user->setRoles(array('ROLE_ADMIN'));

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/registerConseiller', name: 'app_registerConseiller')]
    public function registerConseiller(MailerInterface $mailer,Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
         $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
             $email = (new Email())
            ->from('gk@smart-it-partner.com')
            ->to($form->get('email')->getdata())
            ->subject('Validation Création de compte')
            ->html('<H2>Félicitation votre demande a été validé et votre compte est desormais actif</H2><br><h3>votre login est :</h3><br><h3>et votre mot de passe est :');

        $mailer->send($email);
             // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                     $form->get('plainPassword')->getData()
                 )
            );
             //$user->setRoles(array('ROLE_CLIENT'));
             $user->setRoles(array('ROLE_CONSEILLER'));
             //$user->setRoles(array('ROLE_GUEST'));
         //$user->setRoles(array('ROLE_ADMIN'));

            $entityManager->persist($user);
            $entityManager->flush();
             // do anything else you need here, like send an email
     }

        return $this->render('registration/registerConseiller.html.twig', [
            'registrationForm' => $form->createView(),
         ]);
    }
    // #[Route('/email')]
    // public function sendEmail(MailerInterface $mailer):Response
    // {
    //     $email = (new Email())
    //         ->from('gk@smart-it-partner.com')
    //         ->to('david.mona.mpro@gmail.com')
    //         ->subject('Validation Création de compte')
    //         ->html('<H2>Félicitation votre demande a été validé et votre compte est desormais actif</H2>');

    //     $mailer->send($email);
    //     return new Response("Email Send");

    // }

}
