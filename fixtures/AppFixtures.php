<?php

namespace DataFixtures;

use App\Domain\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;
use function Psl\Iter\random;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        foreach (range(1, 10) as $i) {
            $message = new Message(
                Uuid::v6()->toRfc4122(),
                $faker->sentence,
                random(['sent', 'read'])
            );

            $manager->persist($message);
        }

        $manager->flush();
    }
}
