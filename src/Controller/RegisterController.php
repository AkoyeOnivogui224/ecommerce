<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\RegisterSuccessEvent;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/user/register", name="register_form")
     */
    public function create(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, EventDispatcherInterface $dispatcher)
    {
        $user = new User;
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $em->persist($user);
            $em->flush();

            $registerSuccessEvent = new RegisterSuccessEvent($user);
            $dispatcher->dispatch($registerSuccessEvent, 'register.create');

            return $this->redirectToRoute('security_login');
        }
        $formView = $form->createView();

        return $this->render('register/registerCreate.html.twig', [
            'formView' => $formView
        ]);
    }

    // /**
    //  * @Route("/user/massage/success", name="register_success_email")
    //  */
    // public function showEmailMessageRegister()
    // {
    //     $user = new User;

    //     $this->addFlash("success", "Votre enregistrement s'est derouler avec success veillez consultez votre mail pour la comfirmation");

    //     return $this->render('register/registerSuccess.html.twig', [
    //         'user' => $user
    //     ]);
    // }
}
