<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// Utile la recherche d'url image
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

/**
 * @Route("/authentication", name="authentication")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser() && $this->getUser()->getActivationToken() == null) {
             return $this->redirectToRoute('home');
         }elseif($this->getUser() && $this->getUser()->getActivationToken()){
             $this->redirectToRoute('authentication_login');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return Response
     */
    public function renderLogin()
    {
        /**
         * If the user has already logged in (marked as is authenticated fully by symfony's security)
         * then redirect this user back
         */
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            return $this->redirectToRoute('authentication_login');
        }
        return $this->redirectToRoute('home');
    }
}
