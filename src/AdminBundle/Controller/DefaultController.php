<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Category;
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
        $defaultData = null;
        $eform = $this->createFormBuilder($defaultData)
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
        }

        // Handling Category Remove
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
        }

        return $this->render('@Admin/Default/categories.html.twig',[
            "categories" => $cat,
            "form" => $form->createView(),
            "movies"=>$movies,
            "eform"=>$eform->createView()
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
