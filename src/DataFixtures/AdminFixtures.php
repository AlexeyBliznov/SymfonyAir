<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'supervisor@gmail.com' => new Role('ROLE_SUPERVISOR'),
            'checkIn@gmail.com' => new Role('ROLE_CHECK_IN_MANAGER'),
            'gate@gmail.com' => new Role('ROLE_GATE_MANAGER')
        ];
        foreach($data as $email => $role) {
            $product = new Admin($email, password_hash('24011996', PASSWORD_ARGON2I), $role);
            $product->setName('Test');
            $manager->persist($product);
        }
        
        $manager->flush();
    }
}
