<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Demande;
use App\Form\DemandeType;
use Doctrine\ORM\Mapping\Id;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Repository\DemandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/demande')]
class DemandeController extends AbstractController
{
    #[Route('/', name: 'app_demande_index', methods: ['GET'])]
    public function index(DemandeRepository $demandeRepository, UserRepository $userRepository): Response
    {

        return $this->render('demande/index.html.twig', [
            'demandes' => $demandeRepository->findAll(),
            'conseillers' => $userRepository->findByRole("ROLE_CONSEILLER"),
        ]);
    }
    #[Route('/conseillerDemande', name: 'app_demandeConseiller_index', methods: ['GET'])]
    public function indexConseiller(DemandeRepository $demandeRepository, UserRepository $userRepository): Response
    {

        return $this->render('demande/indexConseiller.html.twig', [
            'demandes' => $demandeRepository->findAll(),
            //'conseillers' => $userRepository->findByRole("ROLE_CONSEILLER"),
        ]);
    }

    #[Route('/new', name: 'app_demande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DemandeRepository $demandeRepository): Response
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //upload des fichiers
            $photoFile = $form->get('photo')->getData();
            $identiteFile = $form->get('identite')->getData();

            //Si fichier uplaoder
            if($photoFile && $identiteFile){
                $originalphotoFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);

                // this is needed to safely include the file name as part of the URL 
                // $safePhotoFilename = transliterator_transliterate('Any Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalphotoFilename);  
                $newPhotoFilename = $originalphotoFilename.'-'.uniqid().'.'.$photoFile->guessExtension(); 
                
                //AND
                
                $originalidentiteFilename = pathinfo($identiteFile->getClientOriginalName(), PATHINFO_FILENAME); 
                
                // this is needed to safely include the file name as part of the URL
                //$safeIdentiteFilename = transliterator_transliterate('Any Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalidentiteFilename);  
                $newIdentiteFilename = $originalidentiteFilename.'-'.uniqid().'.'.$identiteFile->guessExtension(); 
            
                    // Move the file to the directory where brochures are stored  
                    try { 
                        $photoFile->move(
                            $this->getParameter('photos_directory'),$newPhotoFilename);

                        $identiteFile->move( 
                            $this->getParameter('identites_directory'),$newIdentiteFilename);
                    } catch (FileException $e) { 
                    // ... handle exception if something happens during file uplo ad
                        
                    } 
                    // updates the 'brochureFilename' property to store the PDF file name 
                    // instead of its contents 
                    $demande->setPhoto($newPhotoFilename); 
                    $demande->setIdentite($newIdentiteFilename); 
                    $demande->setDatedemande(new \DateTime()); 

                    //Assignation idUser
                    $demande->setIdUser($this->getUser());
            } 
            
            

            //fin upload des fichiers

            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_guest', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/new.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_show', methods: ['GET'])]
    public function show(Demande $demande): Response
    {
        return $this->render('demande/show.html.twig', [
            'demande' => $demande,
        ]);
    }
    #[Route('/affectation', name: 'app_demandeAffecter', methods: ['GET', 'POST'])]
    public function editAffecter(Request $request, DemandeRepository $demandeRepository): Response
    {

        if ($request->getMethod()=="POST") {
            $iddemande = $request->get('iddemande');
            $idConseiller = $request->get('idConseiller');
            $demande = $demandeRepository->find($iddemande);
            $demande->setIdConseiller($idConseiller);
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/affectation.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }
    #[Route('/demandeDecision', name: 'app_demandeDecision', methods: ['GET', 'POST'])]
    public function editDecision(ManagerRegistry $doctrine,Request $request,UserRepository $userRepository, DemandeRepository $demandeRepository, CompteRepository $compteRepository): Response
    {

        if ($request->getMethod()=="POST") {
            $etatDemande = $request->get('idDemande');
            $etat = $request->get('etat');
            $idConseiller=$request->get('idConseiller');
            $type=$request->get('type');
            
            //$idUser=$request->get('idUSer');
            //dd($request->request->get('idUser'));
           $demande = $demandeRepository->findBy(array('id' => $etatDemande));
            //  if($etat=='acceptée'){
                $compte=new Compte();
                $compte->setIdConseiller($idConseiller);
                //$compte->setType($type);
                $client= $userRepository->find($request->request->get('idUser'));
                
                $compte->setIdUser($client);
                $compte->setSolde(0.00);
                $compte->setCreateddAt(new \DateTimeImmutable());
                $compteRepository->save($compte, true);
             //}
            // dd($demande);

            // }elseif($etat=='refusée'){

            // }
            // $demande = $doctrine->getRepository(Demande::class)->findBy(array('idDemande' => $etatDemande));
            //$demande = $demandeRepository->findBy(array('id' => $etatDemande));
            // dd($demande);
            $objet = $demande[0];
            $objet->setEtat($etat);

            $demandeRepository->save($objet, true);
            
            return $this->redirectToRoute('app_dashboardConseiller', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/indexConseiller.html.twig', [
            'ListeDemande' => $objet,
            // 'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demandeRepository->save($demande, true);

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande/edit.html.twig', [
            'demande' => $demande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_delete', methods: ['POST'])]
    public function delete(Request $request, Demande $demande, DemandeRepository $demandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $demandeRepository->remove($demande, true);
        }

        return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
    }
}
