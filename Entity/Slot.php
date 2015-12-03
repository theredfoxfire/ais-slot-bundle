<?php

namespace Ais\SlotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ais\SlotBundle\Model\SlotInterface;
/**
 * Slot
 */
class Slot implements SlotInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $jam;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var boolean
     */
    private $is_delete;


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
     * Set jam
     *
     * @param string $jam
     *
     * @return Slot
     */
    public function setJam($jam)
    {
        $this->jam = $jam;

        return $this;
    }

    /**
     * Get jam
     *
     * @return string
     */
    public function getJam()
    {
        return $this->jam;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Slot
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Slot
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}

