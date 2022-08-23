<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use App\Form\RestaurantType;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use App\Form\ModifyType;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RestaurantRepository $repo1, VilleRepository $repo2): Response
    {
        $restaurants = $repo1->findAll();
        $villes = $repo2->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'restaurants'     => $restaurants,
            'villes'     => $villes,
        ]);
    }

    #[Route('/ville/{nom}', name: 'app_villeRestaurant')]
    public function ville(Ville $ville): Response
    {
        return $this->render('home/ville.html.twig', [
            'controller_name' => 'HomeController',
            'ville'     => $ville,

        ]);
    }

    #[Route('/restaurant/new', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $restaurant = new Restaurant();

        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $restaurant->setProprietaire($this->getUser());

            $manager->persist($restaurant);
            $manager->flush();

            return $this->redirectToRoute('app_restaurant', ['nom' => $restaurant->getNom()]);
        }

        return $this->render('home/create.html.twig', [
            'controller_name' => 'HomeController',
            'formRestaurant'  => $form->createView(),
            'editBtn'         => $restaurant->getId() !== null,
        ]);
    }

    #[Route('/restaurant/delete/{nom}', name: 'app_delete')]
    public function delete(Restaurant $restaurant, EntityManagerInterface $manager): RedirectResponse
    {
        $proprietaire = $this->getUser();
        if( $restaurant->getProprietaire() === $proprietaire){
            $manager->remove($restaurant);
            $manager->flush();

            return $this->redirectToRoute('app_proprietaire');
        }
        else{
            return $this->redirectToRoute('app_restaurant', ['nom' => $restaurant->getNom()]);
        }
    }

    #[Route('/restaurant/edit/{nom}', name: 'app_edit')]
    public function edit(Restaurant $restaurant, Request $request, EntityManagerInterface $manager): Response
    {
        $proprietaire = $this->getUser();
        if( $restaurant->getProprietaire() === $proprietaire){
            $form = $this->createForm(RestaurantType::class, $restaurant);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
    
                $manager->persist($restaurant);
                $manager->flush();
    
                return $this->redirectToRoute('app_restaurant', ['nom' => $restaurant->getNom()]);
            }
    
            return $this->render('home/create.html.twig', [
                'controller_name' => 'HomeController',
                'formRestaurant'  => $form->createView(),
                'editBtn'         => $restaurant->getId() !== null,
            ]);
        }
        else{
            return $this->redirectToRoute('app_restaurant', ['nom' => $restaurant->getNom()]);
        }
    }


    #[Route('/restaurant/{nom}', name: 'app_restaurant')]
    public function restaurant(Restaurant $restaurant): Response
    {
        return $this->render('home/restaurant.html.twig', [
            'restaurant'     => $restaurant,
        ]);
    }

    #[Route('/profile', name: 'app_proprietaire')]
    public function profile(): Response
    {
        return $this->render('home/profile.html.twig', [
        ]);
    }

    #[Route('/profile/edit', name: 'app_editProprietaire')]
    public function editProfile(Request $request, EntityManagerInterface $manager): Response
    {
        $proprietaire = $this->getUser();
        $form = $this->createForm(ModifyType::class, $proprietaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($proprietaire);
            $manager->flush();

            $this->addFlash('message', 'Votre profil a été mis à jour!');
            return $this->redirectToRoute('app_proprietaire');
        }

        return $this->render('home/editProfile.html.twig', [
            'form'            => $form->createView()
        ]);
    }


}
