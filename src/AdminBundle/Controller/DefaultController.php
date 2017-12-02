<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Actor;
use AppBundle\Entity\Category;
use AppBundle\Entity\Movie;
use AppBundle\Form\ActorType;
use AppBundle\Form\CategoryType;
use AppBundle\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{


    /**
     * @Route("/admin",name="admin")
     */
    public function adminAction(Request $request){
        $em = $this -> getDoctrine() -> getManager();

        $aqb = $em->getRepository("AppBundle:Actor")->createQueryBuilder('actor');
        $acnt = $aqb->select('count(actor.id)')->getQuery()->getSingleScalarResult();

        $cqb = $em->getRepository("AppBundle:Category")->createQueryBuilder('category');
        $ccnt = $cqb->select('count(category.id)')->getQuery()->getSingleScalarResult();

        $mqb = $em->getRepository("AppBundle:Movie")->createQueryBuilder('movie');
        $mcnt = $mqb->select('count(movie.id)')->getQuery()->getSingleScalarResult();

        $users = $em->getRepository('UserBundle:User')->findAll();
        return $this->render('@Admin/Default/index.html.twig',[
            "actors"=>$acnt,
            "categories"=>$ccnt,
            "movies"=>$mcnt,
            "users"=>$users
        ]);
    }


    /**
     * @Route("/admin/movies",name="admin_movies")
     */
    public function adminMoviesAction(Request $request){
        return $this->render('@Admin/Default/movies.html.twig');
    }

    /**
     * @Route("/admin/categories",name="admin_categories")
     */
    public function adminCategoriesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $cat = $em->getRepository('AppBundle:Category')->findBy([], ['name' => 'ASC']);
        $movies = $em->getRepository('AppBundle:Movie')->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            $category = new Category();
            $form = $this->createForm(CategoryType::class,$category);
        }


        return $this->render('@Admin/Default/categories.html.twig',[
            "categories" => $cat,
            "form" => $form->createView(),
            "movies"=>$movies
        ]);
    }

    /**
     * @Route("/admin/actors",name="admin_actors")
     */
    public function adminActorsAction(Request $request){
        return $this->render('@Admin/Default/actors.html.twig');
    }

    /**
     * @Route("/admin/users",name="admin_users")
     */
    public function adminUsersAction(Request $request){
        return $this->render('@Admin/Default/users.html.twig');
    }

}
