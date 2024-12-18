<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ModificationAlertService extends AbstractController
{
    public function __construct(
        private Security $security,
        private MailerInterface $mailerInterface,
    ) {
    }

    public function sendEmail(string $url, string $type = 'modifiée'): void
    {
        $user = $this->security->getUser();

        $email = (new TemplatedEmail())
            ->from(new Address($this->getParameter('app.mail_sender'), '"Placeholder Name"'))
            ->to($this->getParameter('app.webmaster'))
            ->subject('Notification de modifications sur le site placeholder')

            // path of the Twig template to render
            ->htmlTemplate('emails/modification_alert.html.twig')

            ->text('La page ' . $this->getParameter('app.site_base_url') . $url . ' a été ' . $type . ' par l\'utilisateur ' . ucfirst($user->getFirstname()) . ' ' . ucfirst($user->getLastname()) . ' .')

            ->context([
                'user' => $user,
                'type' => $type,
                'url' => $url
            ]);

        $this->mailerInterface->send($email);
    }

    public function sendDisableOudatedFacebookPostsEmail(array $outdatedArticles): void
    {
        // $formatedString = '';
        // foreach ($outdatedArticles as $key => $article) {
        //     $formatedString .= 'l\'article du '  . $article[1]->format('d/m/Y') . ' contenant "' . substr($article[0], 0, 50) . '..." n\'est plus mis à jour et a été désactivé \r\n';
        // }


        $email = (new TemplatedEmail())
            ->from(new Address($this->getParameter('app.mail_sender'), '"Lycée Foyen"'))
            ->to($this->getParameter('app.webmaster'))
            ->subject('Désactivation des articles plus mis à jour par Facebook')

            // path of the Twig template to render
            ->htmlTemplate('emails/outdated_articles.html.twig')


            // ->text($formatedString)

            ->context([
                'outdatedArticles' => $outdatedArticles,
            ]);


        $this->mailerInterface->send($email);
    }
}
