<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * page d'accueil
     * http://localhost:8000/
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index(): Response
    {
        $posts=$this->getDoctrine()
        ->getRepository(Post::class)
            ->findAll();
        #on retourne au client une reponse HTTP
        return $this->render('default/index.html.twig',[
            'posts' => $posts
    ]);
    }

    /**
     * Page permettant d'afficher les articles d'une catégorie
     * http://localhost:8000/politique
     * @Route("/{alias}", name="default_category", methods={"GET"})
     */
    public function category(Category $category)
    {
        #dd($category);
        #return new Response("<h1>Page Category : $alias</h1>");
        return $this->render('default/category.html.twig',[
            #'posts'=>$category->getPosts()
            'category'=>$category
        ]);
    }

    /**
     * Pour afficher un article
     * http://localhost:8000/politique/j-aime-les-crepes_1.html
     * @Route("{category}/{alias}_{id}.html", name="default_post", methods={"GET"})
     */
    public function post(Post $post)
    {
        #return new Response("<h1>Page Post : $alias - $id</h1>");
        return $this->render('default/post.html.twig',[
            'post' => $post
        ]);
    }

    /**
     * page contact
     * http://localhost:8000/contact
     * @Route("/contact", name="default_contact", methods={"GET"})
     */
    public function contact()
    {
        #return new Response("<h1>Page Contact</h1>");
        return $this->render('default/contact.html.twig');
    }
}