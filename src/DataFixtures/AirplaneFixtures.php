<?php

namespace App\DataFixtures;

use App\Entity\Airplane;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AirplaneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $models = ['Boeing 747', 'Airbus A330', 'Boeing 777'];
        $maintenanceSchedules  = ['P3M', 'P4M', 'P5M'];
        $now = new \DateTimeImmutable();
        for ($i = 0; $i < 5; $i++) {
            $product = new Airplane($models[array_rand($models)], new \DateTimeImmutable('2023-01-01'), $maintenanceSchedule = new \DateInterval($maintenanceSchedules[array_rand($maintenanceSchedules)]), $now->add($maintenanceSchedule), 'standart');
            $manager->persist($product);
        }

        $manager->flush();
    }
}
