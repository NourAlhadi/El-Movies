<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Form\MovieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }


    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request){
        $movie = new Movie();
        $form = $this -> createForm(MovieType::class,$movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $movie = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
        }

        return $this->render('default/add.html.twig',["form"=>$form->createView()]);
    }

}
