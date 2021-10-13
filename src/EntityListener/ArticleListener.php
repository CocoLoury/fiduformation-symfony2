<?php

namespace App\EntityListener;

use App\Entity\Article;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;

class ArticleListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function prePersist(Article $article, LifecycleEventArgs $event)
    {
        $article->slugify();
    }

    public function preUpdate(Article $article, LifecycleEventArgs $event)
    {
        $article->slugify();
    }
}