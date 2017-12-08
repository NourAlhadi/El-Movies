<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        // Making Sure that First Registered User is admin
        $user = $this->getUser();
        if ($user != null && ($user->getId() == 1 || $user->getUsername() == "admin") ){
            if (!$user->hasRole("ROLE_ADMIN")) {
                $user->addRole('ROLE_ADMIN');
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();
                $this->get('fos_user.user_manager')->updateUser($user);
            }
        }

        // Getting Top 10 new Movies
        $newMovies = $this->getDoctrine()->getManager()
                        ->getRepository('AppBundle:Movie')
                        ->findBy([],['year'=>'DESC'],10);

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
        $movie =$this->getDoctrine()->getManager()->getRepository('AppBundle:Movie')->find($id);

        $form = $this->createFormBuilder(null)
            ->add('comment',TextareaType::class)
            ->add('submit',SubmitType::class)->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $arr = $form->getData();
            $c = new Comment();
            $c->setBody($arr['comment']);
            $c->setWriter($this->getUser()->getName());
            $comments = $this->getDoctrine()->getManager();
            $comments->persist($c);
            $comments->flush();

            $movie->getComments()->add($c);
            $this->getDoctrine()->getManager()->persist($movie);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movie',["id"=>$id]);
        }

        return $this->render('default/movie.html.twig',[
            "movie"=>$movie,
            "form"=>$form->createView(),
        ]);
    }

}
