<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        if ($user != null && ($user->getId() == 1 || $user->getUsername() == "admin") ){
            if (!$user->hasRole("ROLE_ADMIN")) $user->addRole('ROLE_ADMIN');
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('default/index.html.twig');
    }


}
