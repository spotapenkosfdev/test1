<?php

namespace Acme\UserBundle\Entity;
use FOS\UserBundle\Entity\Group as BaseGroup;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usergroup
 *
 * @ORM\Table(name="fos_group")
 * @ORM\Entity(repositoryClass="Acme\UserBundle\Entity\UsergroupRepository")
 */
class Usergroup extends BaseGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
