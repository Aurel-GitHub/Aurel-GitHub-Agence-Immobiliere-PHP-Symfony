<?php

namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification
{



    public function notify(Contact $contact)
    {
        $message = (new \Swift_Message('Agence : ' . $contact->getProperty()->getTitle()))
            ->setFrom('noreply@agenceloremipsum.fr')
            ->setTo('contact@agenceloremipsum.fr')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]), 'text/html');
        $this->mailer->send($message);
    }
}
