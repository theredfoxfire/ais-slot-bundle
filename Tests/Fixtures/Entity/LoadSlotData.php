<?php

namespace Ais\SlotBundle\Tests\Fixtures\Entity;

use Ais\SlotBundle\Entity\Slot;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadSlotData implements FixtureInterface
{
    static public $slots = array();

    public function load(ObjectManager $manager)
    {
        $slot = new Slot();
        $slot->setTitle('title');
        $slot->setBody('body');

        $manager->persist($slot);
        $manager->flush();

        self::$slots[] = $slot;
    }
}
