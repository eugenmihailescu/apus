<?php
namespace Apus\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\Table;

/**
 *
 * @MappedSuperclass()
 * @Table(indexes={@Index(columns={"created_at"}), @Index(columns={"updated_at"})})
 *
 */
abstract class AbstractEntity
{

    /**
     *
     * @Id()
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @Column(type="datetime", nullable=false, options={"default": "0000-00-00 00:00:00", "comment": "The record creation timestamp" })
     */
    protected $createdAt;

    /**
     *
     * @Column(type="datetime", nullable=false, options={"default": "0000-00-00 00:00:00", "comment": "The last record update timestamp" })
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();

        $this->updatedAt = $this->createdAt;
    }

    /**
     * Get the entity primary key
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the datetime the entity has been created
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Get the datetime the entity has been updated
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set the datetime the entity has been created
     *
     * @param \DateTime $createdAt
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set the datetime the entity has been updated
     *
     * @param \DateTime $updatedAt
     * @return self
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
