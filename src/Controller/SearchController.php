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
    public function search(Request $request, ArticleRepository $articleRepository)
    {

        $searchForm = $this->createFormBuilder(null)
            ->add('recherche', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        $searchForm->handleRequest($request);
        $query = $request->request->get('form')['recherche'];
        $foundArticles = $articleRepository->searchArticle($query);

        $results = [];
        foreach ($foundArticles as $article) {
            $results[] = [
                'title' => $article->getTitle(),
                'content' => $article->getContent(),
                'image' => $article->getImage(),
                'createdAt' => $article->getCreatedAt(),
                'category' => $article->getCategory(),
            ];
        }


        var_dump($articleRepository->searchArticle($query));


        return $this->render('search/search.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/results", name="results")
     */
    public function results(Article $article)
    {

       

        return $this->render('search/results.html.twig', [
            ]);
    }
}
