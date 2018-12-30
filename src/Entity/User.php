<?php
namespace Apus\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 *
 * @Entity()
 * @Table(name="user")
 *
 */
class User extends BaseUser
{

    /**
     *
     * @Id()
     * @Column(type="integer", options={"unsigned":true})
     * @GeneratedValue(strategy="AUTO")
     */
    public $id;
}

