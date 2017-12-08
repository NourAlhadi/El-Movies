<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Actor;
use AppBundle\Entity\Category;
use AppBundle\Entity\Movie;
use AppBundle\Form\ActorType;
use AppBundle\Form\CategoryType;
use AppBundle\Form\MovieType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultController extends Controller
{


    /**
     * @Route("/admin/",name="admin")
     */
    public function adminAction(Request $request){
        $em = $this -> getDoctrine() -> getManager();

        $actors = $em->getRepository("AppBundle:Actor")->findBy([],['name'=>'ASC']);

        $categories = $em->getRepository("AppBundle:Category")->findBy([],['name'=>'ASC']);

        $movies = $em->getRepository("AppBundle:Movie")->findBy([],['title'=>'ASC']);

        $users = $em->getRepository('UserBundle:User')->findBy([],['name'=>'ASC']);

        return $this->render('@Admin/Default/index.html.twig',[
            "actors"=>$actors,
            "categories"=>$categories,
            "movies"=>$movies,
            "users"=>$users
        ]);
    }


    /**
     * @Route("/admin/movies",name="admin_movies")
     */
    public function adminMoviesAction(Request $request){

        // Getting Entity Manager / All Movies on System (Ordered)
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository('AppBundle:Movie')->findBy([],['title'=>'ASC']);

        return $this->render('@Admin/Default/movies.html.twig',["movies"=>$movies]);
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
            return $this->redirectToRoute('admin_actors');
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
            return $this->redirectToRoute('admin_actors');
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

        // Getting Entity Manager and all users on system
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findBy([],['name' => 'ASC']);

        return $this->render('@Admin/Default/users.html.twig',["users"=>$users]);
    }

    /**
     * @Route("/admin/user/remove/{username}",name="admin_user_remove")
     */
    public function adminUserRemoveAction(Request $request,$username){
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $userManager->deleteUser($user);
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/user/suspend/{username}",name="admin_user_suspend")
     */
    public function adminUserSuspendAction(Request $request,$username){
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $user->setEnabled(1 - $user->isEnabled());
        $userManager->updateUser($user);
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/user/add/admin/{username}",name="admin_user_add_admin")
     */
    public function adminUserAddAdminAction(Request $request,$username){
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $user->addRole('ROLE_ADMIN');
        $userManager->updateUser($user);
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/user/remove/admin/{username}",name="admin_user_remove_admin")
     */
    public function adminUserRemoveAdminAction(Request $request,$username){
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $user->removeRole('ROLE_ADMIN');
        $userManager->updateUser($user);
        return $this->redirectToRoute('admin_users');
    }


    /**
     * @Route("/admin/movie/edit/{id}",name="admin_movie_edit")
     */
    public function adminMovieEditAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('AppBundle:Movie')->find($id);

        $new_movie = new Movie();

        $eform = $this->createFormBuilder($new_movie)
                ->add('title',TextType::class,array(
                    'required' => false
                ))
                ->add('year',IntegerType::class,array(
                    'required' => false
                ))
                ->add('categories', EntityType::class, [
                    'class'     => 'AppBundle\Entity\Category',
                    'expanded'  => false,
                    'multiple'  => true,
                    'required' => false,
                ])
                ->add('actors', EntityType::class, [
                    'class'     => 'AppBundle\Entity\Actor',
                    'expanded'  => false,
                    'multiple'  => true,
                    'required'  => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                    },
                ])
                ->add('description',TextareaType::class,array(
                    'required' => false
                ))
                ->add('edit',SubmitType::class)
                ->add('remove',SubmitType::class)
                ->getForm();

        $eform->handleRequest($request);

        if ($eform->isSubmitted() && $eform->isValid()) {
            if ($eform->get('remove')->isClicked()){
                $em->remove($movie);
                $em->flush();
                return $this->redirectToRoute('admin_movies');
            }

            $new_movie = $eform->getData();
            //return $this->render('default/dumpper.html.twig',["dmp"=>$new_movie]);
            if ($new_movie->getTitle() != null) {
                $movie->setTitle($new_movie->getTitle());
            }
            if ($new_movie->getYear() != null) {
                $movie->setYear($new_movie->getYear());
            }
            if ($new_movie->getDescription() != null) {
                $movie->setDescription($new_movie->getDescription());
            }
            if (!$new_movie->getActors()->isEmpty()) {
                $movie->setActors($new_movie->getActors());
            }
            if (!$new_movie->getCategories()->isEmpty()) {
                $movie->setCategories($new_movie->getCategories());
            }
            $em->flush();

            return $this->redirectToRoute('admin_movies');
        }

        return $this->render('@Admin/Default/medit.html.twig',[
            "movie"=>$movie,
            "form"=>$eform->createView()
        ]);
    }

    /**
     * @Route("/admin/movie/add/",name="admin_movie_add")
     */
    public function addMovieAction(Request $request){

        // Getting Entity Manager / All Categories on System (Ordered) / All Movies
        $em = $this->getDoctrine()->getManager();

        // Setting Up the Add Movie Form
        $movie = new Movie();
        $form = $this->createForm(MovieType::class,$movie);
        $form->handleRequest($request);


        // Handling Movie Add
        if ($form->isSubmitted() && $form->isValid()){
            $movie = $form->getData();
            $fnd = $em->getRepository('AppBundle:Movie')
                ->findOneBy(array('title'=>$movie->getTitle()));
            if (is_null($fnd)) {
                $em->persist($movie);
                $em->flush();
                $movie = new Movie();
            }
            return $this->redirectToRoute('admin_movies');
        }

        return $this->render('@Admin/Default/madd.html.twig',[
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/admin/movie/poster/{id}",name="admin_movie_poster")
     */
    public function posterMovieAction(Request $request,$id){

        // Getting Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Setting Up the Add Movie Form
        $form = $this->createFormBuilder(null)
                ->add('main',FileType::class,[
                    "required" => false,
                ])
                ->add('index',FileType::class,[
                    "required" => false,
                ])
                ->add('submit',SubmitType::class)
                ->getForm();

        $form->handleRequest($request);


        // Handling Poster Add
        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $em->getRepository('AppBundle:Movie')->find($id);

            $data = $form->getData();
            /** @var UploadedFile $main */
            $main = $data['main'];
            /** @var UploadedFile $index */
            $index = $data['index'];

            if ($main != null){
                $mainExt = $main->getClientOriginalExtension();
                $mainName = md5(uniqid()) . '.' . $mainExt;
                $main->move(
                    $this->getParameter('posters_directory'),
                    $mainName
                );
                $movie->setMainPoster($mainName);
            }

            if ($index != null) {
                $indexExt = $index->getClientOriginalExtension();
                $indexName = md5(uniqid()) . '.' . $indexExt;
                $index->move(
                    $this->getParameter('posters_directory'),
                    $indexName
                );
                $movie->setIndexPoster($indexName);
            }

            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('admin_movies');

        }

        $movie = $em->getRepository('AppBundle:Movie')->find($id);
        return $this->render('@Admin/Default/mposters.html.twig',[
            "form"=>$form->createView(),
            "movie"=>$movie,
        ]);
    }
}
