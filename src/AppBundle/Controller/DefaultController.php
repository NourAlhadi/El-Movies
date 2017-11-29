<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Actor;
use AppBundle\Entity\Category;
use AppBundle\Entity\Movie;
use AppBundle\Form\ActorType;
use AppBundle\Form\CategoryType;
use AppBundle\Form\MovieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


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
     * @Route("/admin/add/movie", name="add")
     */
    public function addMovieAction(Request $request){
        $movie = new Movie();
        $form = $this -> createForm(MovieType::class,$movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $movie = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
        }

        return $this->render('default/addMovie.html.twig',["form"=>$form->createView()]);
    }

    /**
     * @Route("/admin/add/actor")
     */
    public function addActorAction(Request $request){

        $actor = new Actor();
        $form = $this -> createForm(ActorType::class,$actor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $actor = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($actor);
            $em->flush();
        }
        return $this->render('default/addActor.html.twig',["form"=>$form->createView()]);
    }

    /**
     * @Route("/admin/add/category")
     */
    public function addCategoryAction(Request $request){
        $category = new Category();
        $form = $this -> createForm(CategoryType::class,$category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return $this->render('default/addCategory.html.twig',["form"=>$form->createView()]);
    }
}
