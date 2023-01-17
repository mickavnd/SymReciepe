<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    /**
     * Undocumented function
     *
     * @param MailerInterface $mailer
     */
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public  function sendEmail(string $form,string $subject,string $htmlTemplate,array $context, string  $to ='admin@example.com'): void
    {
        
            //email mettre en commentaire  la #Symfony\Component\Mailer\Messenger\SendEmailMessage: async dans  messenger.yaml
            $email = (new TemplatedEmail())
                ->from($form)
                ->to($to)
                ->subject($subject)
                // path of the Twig template to render
                ->htmlTemplate($htmlTemplate)
                // pass variables (name => value) to the template
                ->context($context);

            $this->mailer->send($email);

    }
}