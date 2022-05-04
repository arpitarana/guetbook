<?php

namespace App\Entity\Master;

use Doctrine\Common\Collections\ArrayCollection;

class PermissionType
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    public $resourcePermissions;


    public function __construct()
    {
        $this->resourcePermissions = new ArrayCollection();
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PermissionType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add resourcePermission
     *
     * @param \App\Entity\Master\ResourcePermission $resourcePermission
     *
     * @return PermissionType
     */
    public function addResourcePermission(\App\Entity\Master\ResourcePermission $resourcePermission)
    {
        $this->resourcePermissions[] = $resourcePermission;

        return $this;
    }

    /**
     * Remove resourcePermission
     *
     * @param \App\Entity\Master\ResourcePermission $resourcePermission
     */
    public function removeResourcePermission(\App\Entity\Master\ResourcePermission $resourcePermission)
    {
        $this->resourcePermissions->removeElement($resourcePermission);
    }

    /**
     * Get resourcePermissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResourcePermissions()
    {
        return $this->resourcePermissions;
    }
}
