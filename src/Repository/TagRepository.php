<?php
namespace Apus\Repository;

use Apus\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[] findAll()
 * @method Tag[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{

    const MAX_RESULT = 10;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Find the tags associated with a given post
     *
     * @param int $post_id
     *            The post Id
     * @param int $max
     *            [optional] Specify the max number of results (default 10).
     * @return Tag[] Returns an array of Tag objects
     */
    public function findByPostField(int $post_id, int $max = self::MAX_RESULT): array
    {
        return $this->createQueryBuilder('t', 'id')
            ->andWhere('p.post_id = :post_id')
            ->setParameter('post_id', $post_id)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a tag by the given name
     *
     * @param string $name
     *            The tag's name
     * @return Tag|NULL
     */
    public function findOneByNameField(string $name): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find a tag by the given slug
     *
     * @param string $slug
     *            The tag slug
     * @return Tag|NULL
     */
    public function findOneBySlugField(string $slug): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
