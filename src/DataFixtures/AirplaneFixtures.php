<?php

namespace App\DataFixtures;

use App\Entity\Airplane;
use App\Entity\Flight;
use App\Service\TicketsGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AirplaneFixtures extends Fixture
{
    public function __construct(
        private TicketsGenerator $ticketsGenerator
    ){}

    public function load(ObjectManager $manager): void
    {
        $models = ['Boeing 747', 'Airbus A330', 'Boeing 777'];
        $maintenanceSchedules  = ['P3M', 'P4M', 'P5M'];
        $nowImmutable = new \DateTimeImmutable();
        $cities = ['London', 'Berlin', 'NewYork', 'Paris', 'Kyiv', 'Wroclaw', 'Kharkiv', 'Dresden', 'Vienna'];
        $now = new \DateTime();
        for ($i = 0; $i < 5; $i++) {
            $airplane = new Airplane($models[array_rand($models)], new \DateTimeImmutable('2023-01-01'), $maintenanceSchedule = new \DateInterval($maintenanceSchedules[array_rand($maintenanceSchedules)]), $nowImmutable->add($maintenanceSchedule), 'standart');
            $manager->persist($airplane);
            $airplanes = [$airplane];
        }
        for ($i = 0; $i < 10; $i++) {
                $flight = new Flight($cities[array_rand($cities)], $cities[array_rand($cities)], $airplanes[array_rand($airplanes)], $departureTime = $nowImmutable->add(new \DateInterval('PT' . (string)rand(0,23) . 'H' . (string)rand(0,59) .'M')), $departureTime->add(new \DateInterval('PT2H30M')));
                $manager->persist($flight);
                $this->ticketsGenerator->getTickets($flight, rand(100,300));
            }

        $manager->flush();
    }
}
