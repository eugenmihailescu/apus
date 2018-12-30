<?php
namespace Apus\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 *
 * @Entity(repositoryClass="Apus\Repository\TagRepository")
 * @Table(name="tag", indexes={@Index(columns={"slug"}), @Index(columns={"name"})} )
 *
 */
class Tag extends AbstractEntity
{

    /**
     *
     * @Column(type="integer", nullable=false, options={"unsigned":true, "comment": "The parent post" })
     */
    private $post_id;

    /**
     *
     * @Column(type="string", nullable=false, options={"comment": "The slug" })
     */
    private $slug;

    /**
     *
     * @Column(type="string", nullable=false, options={"comment": "The tag" })
     */
    private $name;

    /**
     *
     * @ManyToOne(targetEntity="Apus\Entity\Post")
     * @JoinColumn(name="post_id", referencedColumnName="id")
     */
    public $post;

    /**
     * Get the tag name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the tag name
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the tag slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set the tag slug
     *
     * @param string $slug
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
