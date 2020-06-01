<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface

{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    const ACTORS = [
        'Andrew Lincoln' => [
            'programs' => [
                'program_0',
                'program_5',
            ]
        ],
        'Norman Reedus' => [
            'programs' => ['program_0']
        ],
        'Lauren Cohan' => [
            'programs' => ['program_0']
        ],
        'Danai Gurira' => [
            'programs' => ['program_0']
        ],
    ];


    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('en_US');

        foreach (self::ACTORS as $actorName => $data){
            $actor = new actor();
            $actor->setName($actorName);
            for ($i=0; $i<sizeof($data['programs']); $i++){
                $actor->addProgram($this->getReference($data['programs'][$i]));
            }
            $slugify = new Slugify();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $manager->persist($actor);
        }

        for ($i=0; $i<=50; $i++){
            $actor = new actor();
            $actor->setName($faker->name);
            for ($n=0; $n<=rand(0, 5); $n++) {
                $actor->addProgram($this->getReference('program_' . rand(0, 5)));
            }
            $slugify = new Slugify();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $manager->persist($actor);


        }
        $manager->flush();
    }
}
