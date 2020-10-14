<?php

namespace App\Notification;

use App\Entity\Contact;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use Twig\Environment;

class ContactNotification
{

//    public function notify(\Swift_Mailer $mailer, Contact $contact)
//    {
//        $message = (new \Swift_Message('Agence : ' . $contact->getProperty()->getTitle()))
//            ->setFrom('noreply@agenceloremipsum.fr')
//            ->setTo('contact@agenceloremipsum.fr')
//            ->setReplyTo($contact->getEmail())
//            ->setBody($this->renderView('emails/contact.html.twig', [
//                'contact' => $contact
//            ]), 'text/html');
//        $mailer->send($message);
//    }

    /***
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @twig
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

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


    /**
     * ok, tu dois declaré ton ContactNotification comme service qui prend comme argument twig, car c'est sa qui pose pose probleme
    yep c'est sa
     */
}
