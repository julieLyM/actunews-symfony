<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/dashboard/post")
 */
class PostController extends AbstractController
{
    /**
     * Creer un article via un formulaire
     * @IsGranted("ROLE_JOURNALISTE")
     * @Route("/create", name="post_create", methods={"GET|POST"})
     * ex. http://localhost:8000/dashboard/post/create
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        #creation d'un nouvel article vide
        $post = new Post();
        $post->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        #Attribution d'un auteur à un article
        $post->setUser($this->getUser());


        #creation du formulaire
        $form = $this->createFormBuilder($post)
            ->add('name', TextType::class, [
                'label' => "Titre de l'article"
            ])
            ->add('category', EntityType::class,
                [
                    'label' => 'Choisir une catégorie',
                    'class' => Category::class,
                    'choice_label' => 'name',
                ])
            ->add('content', TextareaType::class, [
                'label' => false
            ])
            ->add('image', FileType::class,
                [
                    'label' => "Choisis une image"
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => "Publier mon article"
                ])
            ->getForm();

        #permets à symfony de gerer les donnees saisies par l'utilisateur
        $form->handleRequest($request);

        #si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            #dump($request);
            #dd($post);

            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue durant le chargement de votre image.');
                }

                $post->setImage($newFilename);

            } // endif image

            # Génération de l'alias(=sluggerinterface)
            $post->setAlias(
                $slugger->slug(
                    $post->getName()
                )
            );

            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            #Notification de confirmation
            $this->addFlash('success', 'felicitation , votre article est bien ligne');

            # Redirection vers le nouvel article
            return $this->redirectToRoute('default_post', [
                'category' => $post->getCategory()->getAlias(),
                'alias' => $post->getAlias(),
                'id' => $post->getId()
            ]);

        }

        #passer le formulaire à la vue
        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
