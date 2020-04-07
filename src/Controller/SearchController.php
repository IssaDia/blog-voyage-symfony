<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request)
    {

        $searchForm = $this->createFormBuilder(null)
            ->add('recherche', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        
        $searchForm->handleRequest($request);
        $response = $this->forward('App\Controller\SearchController::results', [
            'request' => $request
        ]);

        return $response;
       

        return $this->render('search/search.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);

        
    }

    /**
     * @Route("/results", name="results")
     */
    public function results(ArticleRepository $articleRepository, $request)
    {


        $query = $request->request->get('form')['recherche'];
        $results = $articleRepository->searchArticle($query);

        
        return $this->render('search/results.html.twig', [
            'results' => $results
        ]);
    }
}
