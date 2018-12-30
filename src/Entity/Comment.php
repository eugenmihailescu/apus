<?php
namespace Apus\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Column;

/**
 *
 * @Entity(repositoryClass="Apus\Repository\CommentRepository")
 * @Table(name="comment")
 *
 */
class Comment extends AbstractEntity
{

    /**
     *
     * @Column(type="integer", nullable=false, options={"unsigned":true, "comment": "The parent post" })
     */
    private $post_id;

    /**
     *
     * @Column(type="integer", nullable=false, options={"unsigned":true, "comment": "The comment author" })
     */
    private $user_id;

    /**
     *
     * @Column(type="text", nullable=true, options={"comment": "The comment body" })
     */
    private $content;

    /**
     *
     * @ManyToOne(targetEntity="Apus\Entity\Post")
     * @JoinColumn(name="post_id", referencedColumnName="id")
     */
    public $post;

    /**
     *
     * @ManyToOne(targetEntity="Apus\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    /**
     * Get the comment body
     *
     * @return string|NULL
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the comment's parent post Id
     *
     * @param int $post_id
     * @return self
     */
    public function setPostId(int $post_id): self
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * Set the comment's author Id
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
     * Set the comment body
     *
     * @param string $content
     * @return self
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}

