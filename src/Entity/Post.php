<?php
namespace Apus\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\Table;

/**
 *
 * @Entity(repositoryClass="Apus\Repository\PostRepository")
 * @Table(name="post", indexes={@Index(columns={"title"}), @Index(columns={"slug"}), @Index(columns={"published_at"})} )
 */
class Post extends AbstractEntity
{

    const NUMBER_OF_ITEMS = 10;

    /**
     *
     * @Column(type="string", nullable=false, options={"comment": "The post title" })
     */
    private $title;

    /**
     *
     * @Column(type="string", nullable=false, options={"comment": "The post slug" })
     */
    private $slug;

    /**
     *
     * @Column(type="text", nullable=true, options={"comment": "The post body" })
     */
    private $content;

    /**
     *
     * @Column(type="integer", nullable=false, options={"unsigned":true, "comment": "The post author" })
     */
    private $user_id;

    /**
     *
     * @Column(type="datetime", nullable=true, options={"default": "0000-00-00 00:00:00", "comment": "The post publishig date" })
     */
    private $publishedAt;

    /**
     *
     * @Column(type="string", nullable=true, length=2000, options={"comment": "The post thumbnail URI"})
     */
    private $thumbnail;

    /**
     *
     * @OneToMany(
     *      targetEntity="Apus\Entity\Comment",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     * @OrderBy({"updatedAt"="ASC"})
     */
    public $comments;

    /**
     *
     * @OneToMany(
     *      targetEntity="Apus\Entity\Tag",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     * @OrderBy({"updatedAt"="ASC"})
     */
    public $tags;

    /**
     *
     * @ManyToOne(targetEntity="Apus\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    public function __construct()
    {
        parent::__construct();

        $this->publishedAt = new \DateTime();

        $this->comments = new ArrayCollection();

        $this->tags = new ArrayCollection();
    }

    /**
     * Get the post title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the post slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     *
     * @return string|NULL
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Get the post publishing datetime
     *
     * @return \DateTime|NULL
     */
    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    /**
     * Get the post thumbnail URI
     *
     * @return string|NULL
     */
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    /**
     * Set the post title
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the post slug
     *
     * @param string $slug
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set the post content body
     *
     * @param string $content
     * @return self
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     *
     * @param int $user_id
     * @return self
     */
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Set the post publishing date
     *
     * @param \DateTime $publishedAt
     * @return self
     */
    public function setPublishedAt(?\DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Set the post thumbnail URI
     *
     * @param string $thumbnail
     * @return self
     */
    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Check whether the given user is the author of this Post
     *
     * @param User $user
     * @return bool
     */
    public function isAuthor(User $user = null): bool
    {
        return $user && $user->getId() === $this->getUserId();
    }
}
