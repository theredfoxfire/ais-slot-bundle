<?php

namespace Ais\SlotBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\SlotBundle\Model\SlotInterface;
use Ais\SlotBundle\Form\SlotType;
use Ais\SlotBundle\Exception\InvalidFormException;

class SlotHandler implements SlotHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Slot.
     *
     * @param mixed $id
     *
     * @return SlotInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Slots.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Slot.
     *
     * @param array $parameters
     *
     * @return SlotInterface
     */
    public function post(array $parameters)
    {
        $slot = $this->createSlot();

        return $this->processForm($slot, $parameters, 'POST');
    }

    /**
     * Edit a Slot.
     *
     * @param SlotInterface $slot
     * @param array         $parameters
     *
     * @return SlotInterface
     */
    public function put(SlotInterface $slot, array $parameters)
    {
        return $this->processForm($slot, $parameters, 'PUT');
    }

    /**
     * Partially update a Slot.
     *
     * @param SlotInterface $slot
     * @param array         $parameters
     *
     * @return SlotInterface
     */
    public function patch(SlotInterface $slot, array $parameters)
    {
        return $this->processForm($slot, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param SlotInterface $slot
     * @param array         $parameters
     * @param String        $method
     *
     * @return SlotInterface
     *
     * @throws \Ais\SlotBundle\Exception\InvalidFormException
     */
    private function processForm(SlotInterface $slot, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new SlotType(), $slot, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $slot = $form->getData();
            $this->om->persist($slot);
            $this->om->flush($slot);

            return $slot;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createSlot()
    {
        return new $this->entityClass();
    }

}
