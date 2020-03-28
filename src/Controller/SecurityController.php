<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\SignUpType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="signUp")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder,ValidatorInterface $validator)
    {
        $user = new User();

        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('blog');
        }

        return $this->render('security/signUp.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/connexion", name="signIn")
     */
    public function login(){


        return $this->render('security/signIn.html.twig');

       
    }

    /**
     * @Route("/deconnexion", name="signOut")
     */
    public function logout(){
  
        
        
    }
}

    
