<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Atelier;
use App\Entity\Theme;

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
}
