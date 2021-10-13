<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Serializer\Normalizer\UserNormalizer;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/user/{id}", name="api_user", methods={"GET"})
     */
    public function index(User $user, UserNormalizer $userNormalizer): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [$userNormalizer]; 
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($user, 'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Encoding' => 'UTF-8'
        ]);
    }

    /**
     * @Route("/api/article/{id}", name="api_article", methods={"GET"})
     */
    public function article(Article $article): Response
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                if($object instanceof Category) {
                    return $object->__toString();
                }
            }
        ];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $encoders = [new JsonEncoder()];
        $normalizers = [$normalizer];

        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($article, 'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Encoding' => 'UTF-8'
        ]);
    }

    /**
     * @Route("/api/articles", name="api_articles", methods={"GET"})
     */
    public function articles(ArticleRepository $articleRepository, SerializerInterface $serializer) 
    {
        $articles = $articleRepository->findAll();

        $data = $serializer->serialize($articles, 'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Encoding' => 'UTF-8'
        ]);
    }
}
