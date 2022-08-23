<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request, EntityManagerInterface $manager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $proprietaire = new Proprietaire();

        $form = $this->createForm(InscriptionType::class, $proprietaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $passwordHasher->hashPassword($proprietaire, $proprietaire->getPassword());

            $proprietaire->setPassword($hash);
            $manager->persist($proprietaire);
            $manager->flush();

            return $this->redirectToRoute('app_connexion');
        }

        return $this->render('security/inscription.html.twig',[
            'form' =>$form->createView()
        ]);
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(): Response
    {
        return $this->render('security/connexion.html.twig');
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function deconnexion()
    {
        
    }
}
