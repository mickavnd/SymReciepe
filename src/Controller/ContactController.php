<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        if ($this->getUser()) {
            // dd($this->getUser());
            $contact->setFullName($this->getUser()->getFullName())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);

            $manager->flush();

            //email mettre en commentaire  la #Symfony\Component\Mailer\Messenger\SendEmailMessage: async dans  messenger.yaml
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('admin@example.com')
                ->subject($contact->getSubject())
                // path of the Twig template to render
                ->htmlTemplate('email/contact.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'contact'=> $contact
                ]);

            $mailer->send($email);



            $this->addFlash(
                'success',
                'le mail a bien etait envoyer'
            );

            return $this->redirectToRoute('home.index');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
