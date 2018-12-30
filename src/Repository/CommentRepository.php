<?php
namespace Apus\Repository;

use Apus\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[] findAll()
 * @method Comment[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{

    const MAX_RESULT = 10;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Find the comments posted by the given user Id
     *
     * @param string $user_id
     *            The user id
     * @param int $max
     *            [optional] Specify the max number of results (default 10).
     * @return Comment[] Returns an array of Comment objects
     */
    public function findByUserIdField(string $user_id, int $max = self::MAX_RESULT): array
    {
        return $this->createQueryBuilder('c', 'id')
            ->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the comments associated with a given post
     *
     * @param int $post_id
     *            The post Id
     * @param int $max
     *            [optional] Specify the max number of results (default 10).
     * @return Comment[] Returns an array of Comment objects
     */
    public function findByPostField(int $post_id, int $max = self::MAX_RESULT): array
    {
        return $this->createQueryBuilder('c', 'id')
            ->andWhere('c.post_id = :post_id')
            ->setParameter('post_id', $post_id)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
