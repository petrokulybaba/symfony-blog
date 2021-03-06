<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    private const LAST_COMMENTS_LIMIT = 5;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCountComments()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findLastComments()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->setMaxResults(self::LAST_COMMENTS_LIMIT)
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCommentsByArticleQuery($id)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->join('c.article', 'a')
            ->where('a.id = :article_id')
            ->orderBy('c.created', 'DESC')
            ->setParameter('article_id', $id)
            ->getQuery()
            ;
    }

    public function findAllCommentsQuery()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ;
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
