<?php

namespace Ais\SlotBundle\Model;

Interface SlotInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set jam
     *
     * @param string $jam
     *
     * @return Slot
     */
    public function setJam($jam);

    /**
     * Get jam
     *
     * @return string
     */
    public function getJam();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Slot
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive();

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Slot
     */
    public function setIsDelete($isDelete);

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
