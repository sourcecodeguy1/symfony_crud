<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->redirectToRoute('show');
    }

    /**
     * @Route("/create", name="create")
     * @Method("GET", "POST")
     */
    public function create(Request $request): Response
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('message', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Save Post', 'attr' => ['class' => 'btn btn-primary mt-3']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();

            $entity = $this->getDoctrine()->getManager();

            $entity->persist($post);

            $entity->flush();

            return $this->redirectToRoute('show');
        }

        return $this->render('posts/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show", name="show")
     * @Method("GET")
     */
    public function showPosts():Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('posts/show.html.twig',[
            'posts' => $posts
        ]);
    }


    /*public function editPost($id):Response
    {

        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        return $this->render('posts/edit.html.twig',[
            'post' => $post
        ]);

    }*/

    /**
     * @Route("/post/edit/{id}", name="edit_post")
     * @Method("GET", "PUT")
     */
    public function editPost(Request $request, $id):Response
    {
        $post = new Post();
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('message', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Update Post', 'attr' => ['class' => 'btn btn-primary mt-3']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entity = $this->getDoctrine()->getManager();

            $entity->flush();

            return $this->redirectToRoute('show');
        }

        return $this->render('posts/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/delete/{id}")
     * @Method("DELETE")
     */
    public function deletePost(Request $request, $id):Response
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $entity = $this->getDoctrine()->getManager();

        $entity->remove($post);

        $entity->flush();

        $response = new Response();
        $response->send();
    }

}
