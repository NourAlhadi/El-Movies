<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Actor;
use AppBundle\Entity\Category;
use AppBundle\Form\ActorType;
use AppBundle\Form\CategoryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{


    /**
     * @Route("/admin/",name="admin")
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

        // Getting Entity Manager / All Categories on System (Ordered) / All Movies
        // Needed for later actions
        $em = $this->getDoctrine()->getManager();
        $cat = $em->getRepository('AppBundle:Category')->findBy([], ['name' => 'ASC']);
        $movies = $em->getRepository('AppBundle:Movie')->findAll();


        // Setting Up the Add Category Form
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);

        // Setting Up the Edit / Remove Category Form
        $eform = $this->createFormBuilder(null)
            ->add('categories',EntityType::class,array(
                'class' => 'AppBundle\Entity\Category',
                'data' => $cat
            ))
            ->add('new_name',TextType::class,array(
                'required' => false
            ))
            ->add('edit',SubmitType::class)
            ->add('remove',SubmitType::class)
            ->getForm();

        $eform->handleRequest($request);

        // Handling Category Add
        if ($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $fnd = $em->getRepository('AppBundle:Category')
                ->findOneBy(array('name'=>$category->getName()));
            if (is_null($fnd)) {
                $em->persist($category);
                $em->flush();
                $category = new Category();
                $form = $this->createForm(CategoryType::class, $category);
            }
            return $this->redirectToRoute('admin_categories');
        }

        // Handling Category Edit / Remove
        if ($eform->isSubmitted() && $eform->isValid()){
            $ecat = $eform->get('categories')->getData();
            $ecat = $ecat->getId();
            $ecat = $em->getRepository('AppBundle:Category')->find($ecat);

            if ($eform->get('edit')->isClicked()){
                $new_name = $eform->get('new_name')->getData();
                $ecat->setName($new_name);
                $fnd = $em->getRepository('AppBundle:Category')
                    ->findOneBy(array('name'=>$ecat->getName()));
                if (is_null($fnd)) {
                    $em->persist($ecat);
                    $em->flush();
                    $em->refresh($ecat);
                }
            }
            if ($eform->get('remove')->isClicked()){
                $em->remove($ecat);
                $em->flush();
            }
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('@Admin/Default/categories.html.twig',[
            "categories" => $cat,
            "movies"=>$movies,
            "form" => $form->createView(),
            "eform"=>$eform->createView(),
        ]);
    }

    /**
     * @Route("/admin/actors",name="admin_actors")
     */
    public function adminActorsAction(Request $request){

        // Getting Entity Manager / All Actors on System (Ordered) / All Movies
        // Needed for later actions
        $em = $this->getDoctrine()->getManager();
        $actors = $em->getRepository('AppBundle:Actor')->findBy([], ['name' => 'ASC']);
        $movies = $em->getRepository('AppBundle:Movie')->findAll();


        // Setting Up the Add Actor Form
        $actor = new Actor();
        $form = $this->createForm(ActorType::class,$actor);
        $form->handleRequest($request);

        // Setting Up the Edit / Remove Category Form
        $eform = $this->createFormBuilder(null)
            ->add('actors',EntityType::class,array(
                'class' => 'AppBundle\Entity\Actor',
                'data' => $actors
            ))
            ->add('new_name',TextType::class,array(
                'required' => false
            ))
            ->add('edit',SubmitType::class)
            ->add('remove',SubmitType::class)
            ->getForm();

        $eform->handleRequest($request);

        // Handling Actor Add
        if ($form->isSubmitted() && $form->isValid()){
            $actor = $form->getData();
            $fnd = $em->getRepository('AppBundle:Actor')
                ->findOneBy(array('name'=>$actor->getName()));
            if (is_null($fnd)) {
                $em->persist($actor);
                $em->flush();
                $actor = new Actor();
                $form = $this->createForm(ActorType::class, $actor);
            }
            return $this->redirectToRoute('admin');
        }

        // Handling Actors Edit / Remove
        if ($eform->isSubmitted() && $eform->isValid()){
            $eact = $eform->get('actors')->getData();
            $eact = $eact->getId();
            $eact = $em->getRepository('AppBundle:Actor')->find($eact);

            if ($eform->get('edit')->isClicked()){
                $new_name = $eform->get('new_name')->getData();
                $eact->setName($new_name);
                $fnd = $em->getRepository('AppBundle:Actor')
                    ->findOneBy(array('name'=>$eact->getName()));
                if (is_null($fnd)) {
                    $em->persist($eact);
                    $em->flush();
                    $em->refresh($eact);
                }
            }
            if ($eform->get('remove')->isClicked()){
                $em->remove($eact);
                $em->flush();
            }
            return $this->redirectToRoute('admin');
        }

        return $this->render('@Admin/Default/actors.html.twig',[
            "actors" => $actors,
            "movies"=>$movies,
            "form" => $form->createView(),
            "eform"=>$eform->createView(),
        ]);
    }

    /**
     * @Route("/admin/users",name="admin_users")
     */
    public function adminUsersAction(Request $request){
        return $this->render('@Admin/Default/users.html.twig');
    }

}
