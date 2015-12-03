<?php

namespace Ais\SlotBundle\Tests\Handler;

use Ais\SlotBundle\Handler\SlotHandler;
use Ais\SlotBundle\Model\SlotInterface;
use Ais\SlotBundle\Entity\Slot;

class SlotHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\SlotBundle\Tests\Handler\DummySlot';

    /** @var SlotHandler */
    protected $slotHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DOSEN_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $slot = $this->getSlot();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($slot));

        $this->slotHandler = $this->createSlotHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->slotHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $slots = $this->getSlots(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($slots));

        $this->slotHandler = $this->createSlotHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->slotHandler->all($limit, $offset);

        $this->assertEquals($slots, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $slot = $this->getSlot();
        $slot->setTitle($title);
        $slot->setBody($body);

        $form = $this->getMock('Ais\SlotBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($slot));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->slotHandler = $this->createSlotHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $slotObject = $this->slotHandler->post($parameters);

        $this->assertEquals($slotObject, $slot);
    }

    /**
     * @expectedException Ais\SlotBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $slot = $this->getSlot();
        $slot->setTitle($title);
        $slot->setBody($body);

        $form = $this->getMock('Ais\SlotBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->slotHandler = $this->createSlotHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->slotHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $slot = $this->getSlot();
        $slot->setTitle($title);
        $slot->setBody($body);

        $form = $this->getMock('Ais\SlotBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($slot));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->slotHandler = $this->createSlotHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $slotObject = $this->slotHandler->put($slot, $parameters);

        $this->assertEquals($slotObject, $slot);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $slot = $this->getSlot();
        $slot->setTitle($title);
        $slot->setBody($body);

        $form = $this->getMock('Ais\SlotBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($slot));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->slotHandler = $this->createSlotHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $slotObject = $this->slotHandler->patch($slot, $parameters);

        $this->assertEquals($slotObject, $slot);
    }


    protected function createSlotHandler($objectManager, $slotClass, $formFactory)
    {
        return new SlotHandler($objectManager, $slotClass, $formFactory);
    }

    protected function getSlot()
    {
        $slotClass = static::DOSEN_CLASS;

        return new $slotClass();
    }

    protected function getSlots($maxSlots = 5)
    {
        $slots = array();
        for($i = 0; $i < $maxSlots; $i++) {
            $slots[] = $this->getSlot();
        }

        return $slots;
    }
}

class DummySlot extends Slot
{
}
