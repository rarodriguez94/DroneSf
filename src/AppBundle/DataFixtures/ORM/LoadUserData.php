<?php
/**
 * @author: Raul Rodriguez - raulrodriguez782@gmail.com
 * @created: 7/23/15 - 6:40 PM
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('test');
        $user->setSalt(md5(\uniqid()));
        $encoder = $this->getEncoder($user);
        $password = $encoder->encodePassword('123', $user->getSalt());
        $user->setPassword($password);
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 100;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private function getEncoder($user)
    {
        return $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user);
    }

}