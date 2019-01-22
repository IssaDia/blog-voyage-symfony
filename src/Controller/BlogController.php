<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository;
use App\Entity\Category;
use App\Entity\Comment;


use App\Entity\Article;


class BlogController extends Controller{

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////DISPLAY ARTICLES///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/blog", name="blog")
     */

    public function index()
    {

      $articles = $this->getDoctrine()
        ->getRepository(Article::class)
        ->findAll();

        return $this->render('blog/index.html.twig',['articles'=>$articles]);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////DISPLAY HOME///////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * @Route("/", name="home")
 */

 public function home(){

   return $this->render('blog/home.html.twig');

}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////CREATE OR MODIFY ARTICLE///////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    public function form(Article $article = null, Request $request, ObjectManager $manager){

      if(!$article){

        $article = new Article();
      }

      $form = $this->createFormBuilder($article)
                      ->add('title', TextType::class,array('data_class' => null),null, array('label' => false))
                      ->add('content', TextType::class, array('data_class' => null), array('label' => false))
                      ->add('category', EntityType::class,
                       ['class' => Category::class,
                        'choice_label' =>'title'])
                      ->add('image', FileType::class,array('data_class' => null),null, array('label' => false))
                      ->getForm();

      $form->handleRequest($request);


      if ($form->isSubmitted() && $form->isValid()) {

          $file = $article->getImage();
          $filename = $file->getClientOriginalName();

          try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
          $article->setImage($filename);

          $article->setCreatedAt(new \DateTime());

        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute('blog', ['id' => $article->getId()]);

      }

      return $this->render('blog/create.html.twig',
      ['formArticle' => $form->createView(),'editMode' => $article->getId() !== null
    ]);

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////DELETE ARTICLE/////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/blog/{id}/delete", name="blog_delete")
     * @Route("/blog/article/{id}", name="blog_show")
     */


    public function delete(Article $article, Request $request, ObjectManager $manager){


        $manager = $this->getDoctrine()->getManager();
        $manager->remove($article);
        $manager->flush();

        return $this->redirectToRoute('blog', ['id' => $article->getId()]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////DISPLAY ARTICLE AND COMMENTS///////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/blog/article/{id}", name="blog_show")
     */

    public function show($id ,Article $article = null, Request $request, ObjectManager $manager){

      ///display article by ID

      $articles = $this->getDoctrine()
      ->getRepository(Article::class)
      ->find($id);

      ///create new comment form

      $comment = new Comment();

      $form = $this->createFormBuilder($comment)
              ->add('author',null, array('label' => false))
              ->add('content',null, array('label' => false))
              ->getForm();

      ///repatriate comment to database

      $form->handleRequest($request);

      $manager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

              $comment->setCreatedAt(new \DateTime());
              $comment->setArticle($article);

              $manager->persist($comment);
              $manager->flush();

              return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);

              }

      return $this->render('blog/show.html.twig',['article'=>$articles,'comments'=>$article->getComments(), 'formComment' => $form->createView()]);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////DELETE ARTICLE/////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     *@Route("/blog/deleteComment/{id}", name="blog_deleteComment")
     */


    public function deleteComment($id, Request $request, ObjectManager $manager){

      ///get id from comment

      $comment = $this->getDoctrine()
      ->getRepository(Comment::class)
      ->find($id);


      ///delete id from database

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('blog', ['id' => $comment->getId()]);
    }

}
