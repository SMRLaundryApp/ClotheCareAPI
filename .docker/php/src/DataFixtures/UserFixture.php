<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{

    /** @var ObjectManager */
    private $manager;

    /** @var Generator */
    protected $faker;

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) use ($manager) {
            $name=$this->faker->name('male');
            $user = new User();
            $username=str_replace(' ', '', $name);
            $user->setUsername($username);
            $user->setEmail($username.'@example.com');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));

            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);
            $manager->persist($apiToken1);
            $manager->persist($apiToken2);

            return $user;
        });

        $this->createMany(3, 'admin_users', function($i) {
            $name=$this->faker->name('male');
            $username=str_replace(' ', '', $name);
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($username.'@thespacebar.com');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));

            return $user;
        });

        $manager->flush();
    }
}
