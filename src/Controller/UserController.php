<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * this controller  allow us to edit user profile
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER')  and user  === chossenUser")]
    #[Route('/utilisateur/edition/{id}', name: 'User_edit', methods: ['GET', 'POST'])]
    public function edit(User $chossenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security_login');
        }

        if (!$this->getUser() === $chossenUser) {

            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(UserType::class, $chossenUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($chossenUser, $form->getData()->getPlainPassword())) {
                $user = $form->getData();

                $manager->persist($user);

                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre Profile a bien ete modifier'
                );


                return $this->redirectToRoute('home.index');
            }else{
                $this->addFlash(
                    'warning',
                    'le mot de passe renseigner et incorrect'
                );
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Security("is_granted('ROLE_USER')  and user  === chossenUser")]
    #[Route('/utilisateur/edition-mot-de-passe/{id}', name:'User_edit_Password', methods:['GET','POST'])]
    public function editPassword( User $chossenUser, Request $request ,EntityManagerInterface $manager,UserPasswordHasherInterface $hasher) :Response
    {       
        $form =$this->createForm(UserPasswordType::class);

            $form ->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                if($hasher->isPasswordValid($chossenUser, $form->getData()['plainPassword']))
                {
                    $chossenUser->setUpdateAt(new \DateTimeImmutable());
                    $chossenUser->setPlainPassword(
                        $form-> getData()['newPassword']
                    );

                    $manager->persist($chossenUser);

                    $manager->flush();

                    $this->addFlash(
                        'success',
                        'le mot de passe a bien etait modifier'
                    );

                    return $this->redirectToRoute('home.index');

                }else{
                    $this->addFlash(
                        'warning',
                        'le mot de passe  incorrect'
                    );
                }

            }



        return $this->render('pages/user/edit_password.html.twig',[
            'form' => $form->createView()
        
        ]
    
    
    );

    }


}
