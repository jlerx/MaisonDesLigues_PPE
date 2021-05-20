<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Atelier;
use App\Entity\Theme;
use App\Form\AtelierType;
use App\Form\ThemeType;
use App\Form\VacationType;



class MainController extends AbstractController
{
    /**
     * @Route("/atelier", name="atelier")
     */
    public function index(): Response
    {
        $EntityManager = $this->getDoctrine()->getManager();
        $atelier = $EntityManager->getRepository(Atelier::class)->findAll();
        /*
        $theme= new Theme();
        $theme->setLibelle('libelletest');

        $at = new Atelier();
        $at->setLibelle('libelatel');
        $at->setNbPlacesMaxi(35);
        $at->addTheme($theme);

        $EntityManager->persist($theme);
        $EntityManager->persist($at);
        $EntityManager->flush();
        */
        return $this->render('main/atelier.html.twig', [
            'controller_name' => 'MainController',
            'atelier' => $atelier,
        ]);
    }


    /**
     * @Route("/atelier/create", name="atelier_create")
     */
    public function create(Request $request): Response
    {

        $formAtelier = $this->createForm(AtelierType::class);
        $formTheme = $this->createForm(ThemeType::class);
        $formVacation = $this->createForm(VacationType::class);

        return $this->render('main/atelierCreate.html.twig', [
            'controller_name' => 'MainController',
            'formAtelier' => $formAtelier->createView(),
            'formTheme' => $formTheme->createView(),
            'formVacation' => $formVacation->createView(),
        ]);

    }
}
