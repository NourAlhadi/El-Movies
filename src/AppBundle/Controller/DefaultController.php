<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {

        // Getting Top 10 new Movies
        $newMovies = $this->getDoctrine()->getManager()
                        ->getRepository('AppBundle:Movie')
                        ->findBy([],['year'=>'DESC'],12);

        // Getting Top 10 rated Movies
        $qb = $this->getDoctrine()->getRepository('AppBundle:Movie')->createQueryBuilder('qb');
        $qb->addOrderBy('qb.starsCount / qb.totalCounts','DESC')->setMaxResults(10);
        $topRated = $qb->getQuery()->execute();

        return $this->render('default/index.html.twig',[
            "new_movies"=>$newMovies,
            "top_rated"=>$topRated,
        ]);
    }


    /**
     * @Route("/movie/{id}/",name="movie")
     */
    public function movieAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();

        $movie =$em->getRepository('AppBundle:Movie')->find($id);

        if ($movie == null){
            return $this->render('default/error404.html.twig');
        }

        $form = $this->createFormBuilder(null)
            ->add('comment',TextareaType::class)
            ->add('submit',SubmitType::class)->getForm();

        $form->handleRequest($request);

        $views = $movie->getViewCount();

        if ($form->isSubmitted() && $form->isValid()){
            $arr = $form->getData();
            $c = new Comment();
            $c->setBody($arr['comment']);
            $c->setWriter($this->getUser()->getName());
            $em->persist($c);
            $em->flush();

            $movie->getComments()->add($c);
            $movie->setViewCount($views-1);
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('movie',["id"=>$id]);
        }else {
            $movie->setViewCount($views + 1);
            $em->persist($movie);
            $em->flush();
        }
        return $this->render('default/movie.html.twig',[
            "movie"=>$movie,
            "form"=>$form->createView(),
        ]);
    }

    /**
     * @Route("/movie/add/rate",name="add_rate")
     *
     * @Method({"GET", "POST"})
     */
    public function addRateAction(Request $request){
        //return $this->render('default/dumpper.html.twig',["dmp"=>$request]);
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            dump($content);
            if (!empty($content)) {
                $params = json_decode($content, true);
                dump($params);
                $id = $params['id'];
                $stars = $params['stars'];

                $em = $this->getDoctrine()->getManager();
                $movie = $em->getRepository('AppBundle:Movie')->find($id);

                $starcnt = $movie->getStarsCount();
                $totalcnt = $movie->getTotalCounts();

                $movie->setTotalCounts($totalcnt+1);
                $movie->setStarsCount($starcnt+$stars);

                $em->persist($movie);
                $em->flush();
            }
            return new JsonResponse(array('data' => $params));
        }

        return new Response('Error!', 400);
    }


    /**
     * @Route("/filter/",name="movie_filter")
     *
     * @Method("POST")
     */
    public function filterAction(Request $request){
        $data = $request->get('search');

        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository('AppBundle:Movie')->findAll();
        $actors = $em->getRepository('AppBundle:Actor')->findAll();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('default/filter.html.twig',[
            'data'=>$data,
            'movies'=>$movies,
            'categories'=>$categories,
            'actors'=>$actors
        ]);
    }


    /**
     * @Route("/actor/{id}",name="actor")
     */
    public function actorAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $actor = $em->getRepository('AppBundle:Actor')->find($id);

        if ($actor == null){
            return $this->render('default/error404.html.twig');
        }

        $movies = $em->getRepository('AppBundle:Movie')->findAll();
        return $this->render('default/actor.html.twig',[
            'actor'=>$actor,
            'movies'=>$movies,
        ]);
    }

    /**
     * @Route("/category/{id}",name="category")
     */
    public function categoryAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if ($category == null){
            return $this->render('default/error404.html.twig');
        }

        $movies = $em->getRepository('AppBundle:Movie')->findAll();
        return $this->render('default/category.html.twig',[
            'category'=>$category,
            'movies'=>$movies,
        ]);
    }

    /**
     * @Route("/categories/",name="categories")
     */
    public function categoriesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findBy([],['name'=>'ASC']);
        return $this->render('default/categories.html.twig',[
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/actors/",name="actors")
     */
    public function actorsAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $actors = $em->getRepository('AppBundle:Actor')->findBy([],['name'=>'ASC']);
        return $this->render('default/actors.html.twig',[
            'actors' => $actors
        ]);
    }

    /**
     * @Route("/movies/top/",name="top_rated")
     */
    public function topRatedAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        // Getting Top 10 rated Movies
        $qb = $em->getRepository('AppBundle:Movie')->createQueryBuilder('qb');
        $qb->addOrderBy('qb.starsCount / qb.totalCounts','DESC')->setMaxResults(10);
        $topRated = $qb->getQuery()->execute();

        return $this->render('default/movie_viewer.html.twig',[
           "movies" => $topRated,
            "tag" => "Top Rated"
        ]);
    }
    /**
     * @Route("/movies/new/",name="new_movies")
     */
    public function newMoviesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        // Getting Top 10 rated Movies
        // Getting Top 10 new Movies
        $newMovies = $em->getRepository('AppBundle:Movie')->findBy([],['year'=>'DESC'],12);

        return $this->render('default/movie_viewer.html.twig',[
            "movies" => $newMovies,
            "tag" => "New Movies"
        ]);
    }

}
