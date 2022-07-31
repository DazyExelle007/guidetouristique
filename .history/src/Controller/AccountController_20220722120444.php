<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account")
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("", name="app_account", methods="GET")
     */
    public function show(UserRepository $user): Response
    {
        
      
        return $this->render('account/show.html.twig', [
            'guides' =>$user,
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/edit", name="app_account_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em,UserRepository $user, FlashyNotifier $flash): Response
    {
        
        $user = $this->getUser();

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            //$this->addFlash('success', 'Account updated successfully');

            $flash->success('Compte mis à jour avec succès');

            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit.html.twig', [
            'guides'=>$user,
            'controller_name' => 'AccountController',
            'form'=>$form->createView()
        ]);
    }

     /**
     * @Route("/change-password", name="app_account_change_password",  methods={"GET","POST"})
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $PasswordHash, UserRepository $user): Response
    {
   
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required'=> true
        ]);
        $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid())
         {
             $user->setPassword(
                $PasswordHash->hashPassword($user,$form['newPassword']->getData())
             );


             $em->flush();

             $this->addFlash('success', 'Password updated successfully');

             return $this->redirectToRoute('app_account');
         
         }
        return $this->render('account/change_password.html.twig', [
            'guides'=>$user,
            'controller_name' => 'AccountController',
            'form'=>$form->createView(),
        ]);
    }
}
