<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Program;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\Slugify;
use Faker;

class AppFixtures extends Fixture
{
    public function getDependencies()
    {
        return [CategoryFixtures::class, ActorFixtures::class];
    }

    public function load(ObjectManager $manager)
    {

    }
}
