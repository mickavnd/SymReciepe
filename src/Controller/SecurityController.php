<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    
    /**
     * this function   is for login   with the firewall of symfony
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/connexion', name: 'security_login',methods:['GET','POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        
        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'last_Username' => $authenticationUtils->getLastUsername(),
            'error'=>$authenticationUtils->getLastAuthenticationError()
            
        ]);
    }

 /**
     * logout function  with the fireWall of symfony
     *
     * @return void
     */
    #[Route('/deconnexion', name: 'security_logout')]
    public function logOut()
    {

    }
    #[Route('/inscription',name: 'security_registration',methods:['GET','POST'])]
    /**
     * this controoller allow us to register
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function resgistration(Request $request, EntityManagerInterface $manager) : Response
    {
         $user = new User();
         $user->setRoles(['ROLE_USER']);
         $form = $this->createForm(RegistrationType::class,$user);

         
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
              
            
            $manager->persist($user);

            $manager->flush();
        
           

            $this->addFlash(
                 'success',
                'vous ete a bien inscrie'
            );

            return $this->redirectToRoute('home.index');

        }
        else{

            $this->addFlash(
                'danger',
                'erreur dans le formulaire'
           );

        }


        return $this->render('pages/security/registration.html.twig',[
            'form' => $form->createView()
        ]
    );
    }
}
