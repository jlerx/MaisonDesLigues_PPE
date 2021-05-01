<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\SecurityController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $pathLogo = SecurityController::getImage('mdl.png');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'pathLogo' => $pathLogo,
        ]);
    }
}
