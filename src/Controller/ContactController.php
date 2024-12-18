<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/ecrire-a-must-menager', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailerInterface): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('check')->getData()) {
                $email = (new TemplatedEmail())
                    ->from(new Address($this->getParameter('app.mail_sender'), '"Must Ménager"'))
                    ->to($this->getParameter('app.webmaster'))
                    ->subject($contact->getTitle())
    
                    // path of the Twig template to render
                    ->htmlTemplate('emails/contact.html.twig')
    
                    ->text('Vous avez reçu un message de ' . $contact->getEmail() . ' sur le formulaire de contact du site must-menager.fr.
                    Sujet : ' . $contact->getTitle() . '.
                    Contenu du message :  ' . $contact->getMessage() . '.')

                    ->context([
                        'contact' => $contact,
                    ])
                ;

                $contact
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setIsRead(0)
                ;
    
                $mailerInterface->send($email);
    
                $entityManager->persist($contact);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Votre message a bien été transmis.');
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
