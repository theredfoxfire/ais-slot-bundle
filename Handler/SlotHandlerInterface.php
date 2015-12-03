<?php

namespace Ais\SlotBundle\Handler;

use Ais\SlotBundle\Model\SlotInterface;

interface SlotHandlerInterface
{
    /**
     * Get a Slot given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return SlotInterface
     */
    public function get($id);

    /**
     * Get a list of Slots.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Slot, creates a new Slot.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return SlotInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Slot.
     *
     * @api
     *
     * @param SlotInterface   $slot
     * @param array           $parameters
     *
     * @return SlotInterface
     */
    public function put(SlotInterface $slot, array $parameters);

    /**
     * Partially update a Slot.
     *
     * @api
     *
     * @param SlotInterface   $slot
     * @param array           $parameters
     *
     * @return SlotInterface
     */
    public function patch(SlotInterface $slot, array $parameters);
}
