<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

use App\Controller\SecurityController;

/**
    * @Route("/admin", name="admin")
*/
class AdminController extends AbstractController
{
    /**
     * @Route("/show", name="_show")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $pathLogo = SecurityController::getImage('mdl.png');
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
            'pathLogo' => $pathLogo,
        ]);
    }
}
