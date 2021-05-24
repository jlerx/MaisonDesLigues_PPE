<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Compte;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use App\Controller\SecurityController;

/**
 * @Route("/registration", name="registration")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // On génère un token et on l'enregistre
            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            
            $compte = $entityManager->getRepository(Compte::class)->findOneBy(['numLicence' => $user->getNumLicence()]);
            if($compte == null){

                $this->addFlash(
                    'error',
                    'Le numéro de licence '.$user->getNumLicence().' n\'est associé à aucun compte...' 
                );
                return $this->redirectToRoute('registration_register');

                /**
                // On renvoie une erreur 404
                throw $this->createNotFoundException('Ce compte n\'exite pas');
                */
            }else{
                $user->setCompte($compte);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            // On crée le message
            $message = (new \Swift_Message('Nouveau compte'))
                // On attribue l'expediteur
                ->setFrom('votre@adresse.fr')
                // On attrivue le destinataire
                ->setTo($compte->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                )
                ;
                $mailer->send($message);

            $this->addFlash(
                'success',
                'Votre compte a bien été crée, verifier votre compte, nous vous avons envoyé un mail'
            );

            return $this->redirectToRoute('authentication_login');
        }


        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/activation/{token}", name="_activation")
     */
    public function activation($token, UserRepository $users)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $users = $users->findOneBy(['activation_token' => $token]);

        // Si  aucun utilisateur n'est associé à ce token
        if(!$users){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'exite pas');
        }

        // On supprime le token
        $users->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($users);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');

        // On retourne à l'acceuil
        return $this->redirectToRoute('home');
    }

}
