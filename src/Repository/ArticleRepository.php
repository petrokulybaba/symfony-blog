<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    private const LAST_ARTICLES_LIMIT = 5;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getCountArticles()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findLastArticles()
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->setMaxResults(self::LAST_ARTICLES_LIMIT)
            ->orderBy('a.created', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findArchiveList()
    {
        return $this->createQueryBuilder('a')
            ->select('Month(a.created) AS month', 'Year(a.created) AS year')
            ->groupBy('month', 'year')
            ->orderBy('year', "DESC")
            ->getQuery()
            ->getResult()
            ;
    }

    public function findArticlesByCategoryQuery($id)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.category', 'c')
            ->where('c.id = :category_id')
            ->orderBy('a.created', 'DESC')
            ->setParameter('category_id', $id)
            ->getQuery()
            ;
    }

    public function findArticlesByTagQuery($id)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.tag', 't')
            ->where('t.id = :tag_id')
            ->orderBy('a.created', 'DESC')
            ->setParameter('tag_id', $id)
            ->getQuery()
            ;
    }

    public function findArticlesByArchiveQuery($month, $year)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where('Month(a.created) = :month', 'Year(a.created) = :year')
            ->orderBy('a.created', 'DESC')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->getQuery()
            ;
    }

    public function findAllArticlesQuery()
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->orderBy('a.created', 'DESC')
            ->getQuery()
            ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
