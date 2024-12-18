<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerPasswordType;
use App\Form\CustomerType;
use App\Security\AppCustomerAuthenticator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\SecurityBundle\Security\UserAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

#[Route('/')]
class CustomerController extends AbstractController
{
    #[Route('mon-compte', name: 'app_customer_show', methods: ['GET'])]
    public function show()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        return $this->render('customer/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('mon-compte/modifier', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CustomerType::class, $this->getUser());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Votre compte a été modifié avec succès.');
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/edit.html.twig', [
            'user' => $this->getUser(),
            'form' => $form
        ]);
    }

    #[Route('mon-compte/mot-de-passe', name: 'app_customer_password_edit', methods: ['GET', 'POST'])]
    public function password(Security $security, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $customer = $security->getUser();
        $form = $this->createForm(CustomerPasswordType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if (!$hasher->isPasswordValid($customer, $form->get('formerPassword')->getData())) {
                $this->addFlash('alert', 'Erreur d\'authetification, le mot de passe n\'a pas pu être modifié.');
                // throw new AccessDeniedHttpException();
                return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
            }
            // Assign new password to $customer (or currentUser ??)
            $customer->setPassword($hasher->hashPassword($customer, $form->get('plainPassword')->getData()));
            $em->flush();
            $this->addFlash('success', 'access ok !');
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('customer/edit.html.twig', [
            'user' => $this->getUser(),
            'form' => $form
        ]);
    }

    #[Route('m-enregistrer', name:'app_customer_sign_up', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher, UserAuthenticatorInterface $userAuthenticator, AppCustomerAuthenticator $authenticator): Response
    {
        // if user is already connected, add flash error message && return to main
        if($this->getUser()){
            $this->addFlash('alert', 'Vous êtes déjà connecté en tant que ' . $this->getUser()->getEmail() . '.');
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer, ['is_new' => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $customer
                ->setPassword($hasher->hashPassword($customer, $form->get('password')->getData()))
                ->setRoles(['ROLE_CUSTOMER'])
                ->setCreatedAt(new DateTimeImmutable());
            $em->persist($customer);
            $em->flush();

            return $userAuthenticator->authenticateUser(
                $customer,
                $authenticator,
                $request
            );
            // return $this->redirectToRoute('app_customer_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }
}