<?php
namespace Apus\Repository;

use Apus\Entity\Post;
use Apus\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[] findAll()
 * @method Post[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{

    const MAX_RESULT = 10;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Find the posts by the given user Id
     *
     * @param string $user_id
     *            The user id
     * @param int $max
     *            [optional] Specify the max number of results (default 10).
     * @return Post[] Returns an array of Post objects
     */
    public function findByUserIdField(string $user_id, int $max = self::MAX_RESULT): array
    {
        return $this->createQueryBuilder('p', 'id')
            ->andWhere('p.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the posts by the given tags
     *
     * @param array $tags
     *            An array of tags
     * @param int $max
     *            [optional] Specify the max number of results (default 10).
     * @return Post[] Returns an array of Post objects
     */
    public function findByTagsField(array $tags, int $max = self::MAX_RESULT): array
    {
        return $this->findPosts('p', 'id')
            ->innerJoin(Tag::class, 't', Join::WITH, 'p.id = t.post_id')
            ->andWhere('t.name IN (:tags)')
            ->setParameter('tags', $tags)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the posts with a similar title
     *
     * @param string $pattern
     *            The title formatted as MySQL RLIKE
     * @param int $max
     *            [optional] Specify the max number of results (default 10).
     * @see https://dev.mysql.com/doc/refman/5.7/en/regexp.html
     * @return Post[] Returns an array of Post objects
     */
    public function findBySimilarTitleField(string $pattern, int $max = self::MAX_RESULT): array
    {
        return $this->findPosts('p', 'id')
            ->andWhere('t.title RLIKE :pattern')
            ->setParameter('pattern', $pattern)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a post by the given slug
     *
     * @param string $slug
     *            The post slug
     * @return Post|NULL
     */
    public function findOneBySlugField(string $slug): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
