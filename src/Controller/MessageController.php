<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    public function __construct(private ManagerRegistry $manager) {}
    // #[Route('/message', name: 'app_message')]
    // public function index(): Response
    // {
    //     return $this->render('message/contact.html.twig', [
    //         'controller_name' => 'MessageController',
    //     ]);
    // }

    #[Route('/message', name: 'app_message')]
    public function new(Request $reqest, EntityManagerInterface $manager): Response
    {
        // echo "ok";
        // var_dump($request);
        if ($reqest->request->count() > 0) {
            # code...
            $message = new Message();
            $message->setNom($reqest->request->get('Nom'))
                    ->setContenu($reqest->request->get('Contenu'))
                    ->setEmail($reqest->request->get('Email'))
                    ->setTelephone($reqest->request->get('Telephone'))
                    ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($message);
            $manager->flush();
        }
        // ...

        // $form = $this->createForm(MessageType::class, $message);

        return $this->render('message/contact.html.twig', [
            'message' => "Message envoyé",
        ]);
    }
}