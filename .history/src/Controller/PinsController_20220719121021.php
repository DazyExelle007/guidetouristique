<?php

namespace App\Controller;
use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/publications", name="app_publications", methods="GET")
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy( [], ['createdAt' => 'DESC']);

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/create", name="app_publications_create" , methods="GET|POST")
     * @Security("is_granted('ROLE_USER') && user.isVerified() == true")
     */

    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo, FlashyNotifier $flashy): Response
    {

       
       $pin = new Pin;
dd($pin);
       $form = $this->createForm(PinType::class, $pin);
           
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           // $janeDoe = $userRepo->findOneBy(['email'=> 'janedoe@example.com']);
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            //$this->addFlash('success', 'Pin successfully created');
            $flashy->success('Pin successfully created');
            return $this->redirectToRoute('app_publications');

        }
        
        return $this->render('pins/create.html.twig', [
            'guides'=>$userRepo,
            'form'=> $form->createView()
            ]);

    }

    /**
     * @Route("pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     */
    public function show(Pin $pin): Response
    {
     
        return $this->render('pins/show.html.twig', compact('pin'));
    }


    /**
     * @Route("pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "POST"})
     *  @Security("is_granted('ROLE_USER') && user.isVerified() && pin.getUser() == user ")
     */
    public function edit(Request $request, EntityManagerInterface $em, Pin $pin, FlashyNotifier $flashy): Response
    {

    $form = $this->createForm(PinType::class, $pin );
    
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $em->persist($pin);
        $em->flush();
      //  $this->addFlash('success', 'Pin successfully updated');

        $flashy->success('Pin successfully updated');
        return $this->redirectToRoute('app_home');

    }

        return $this->render('pins/edit.html.twig',
         [
             'pin'=> $pin,
             'form'=> $form->createView()
         ]);
    }

    /**
     * @Route("pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods={"POST"})
     * 
     *  @Security("is_granted('ROLE_USER') && user.isVerified() && pin.getUser() == user ")
     */
    public function delete(Request $request, EntityManagerInterface $em, Pin $pin): Response
    {
      
        
        if($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->request->get('csrf_token')))
        {
            $em->remove($pin);
            $em->flush();

            $this->addFlash('info', 'Pin successfully deleted!');
    
        }
    
        return $this->redirectToRoute('app_home');
    }
}
