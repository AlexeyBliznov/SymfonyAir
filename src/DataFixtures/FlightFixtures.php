<?php

namespace App\DataFixtures;

use App\Entity\Airplane;
use App\Entity\Flight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FlightFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cities = ['London', 'Berlin', 'NewYork', 'Paris'];
        $now = new \DateTime();
        for ($i = 0; $i < 10; $i++) {
            $product = new Flight($cities[array_rand($cities)], $cities[array_rand($cities)], mt_rand(1,5), $now->format('Y-m-d'), '00', '00');
            $manager->persist($product);
        }

        $manager->flush();
    }
}