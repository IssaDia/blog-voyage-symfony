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


        if ($searchForm->handleRequest($request)->isSubmitted() && $searchForm->isValid()) {
            $query = $request->request->get('form')['recherche'];
        $results = $articleRepository->searchArticle($query);

        return $this->render('search/results.html.twig', [
            'results' => $results
        ]);
        };


        return $this->render('search/search.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }

}
