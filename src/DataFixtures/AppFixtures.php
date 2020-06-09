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
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        for ($i = 1; $i <= 1000; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $slug = $slugify->generate($category->getName());
            $category->setSlug($slug);
            $manager->persist($category);
            $this->addReference("category_" . $i, $category);
        }
        for ($i = 1; $i <= 1000; $i++) {
            $program = new Program();
            $program->setTitle($faker->sentence(4, true));
            $program->setSummary($faker->text(100));
            $program->setCategory($this->getReference("category_" . rand(1, 1000)));
            //$program->setYear($faker->year($max = 'now'));
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $this->addReference("program_" . $i, $program);
            $manager->persist($program);

        for ($j = 1; $j <= 5; $j++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference("program_" . $i));
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);
            $manager->persist($actor);
        }
    }
        $manager->flush();
    }
}
