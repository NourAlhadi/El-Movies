<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Service\GetMyName;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/{name}", name="homepage", requirements={"name":"[a-zA-Z0-9]*"} )
     */
    public function indexAction(Request $request, $name)
    {
        // this is service call
        //$myName = $this->get('App.GetMyName')->getName();

        return $this->render('default/index.html.twig', array("username"=>$name) );
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        return $this->render('default/about.html.twig');
    }

    /**
     * @Route("/age/{age}", name="age", requirements={"age":"[0-9]*"})
     */
    public function ageAction(Request $request, $age)
    {
        return $this->render('default/age.html.twig',["age"=>$age]);
    }

}
