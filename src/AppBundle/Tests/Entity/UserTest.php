<?php
/**
 * @author: Raul Rodriguez - raulrodriguez782@gmail.com
 * @created: 8/12/16 - 2:54 AM
 */

namespace AppBundle\Tests\Entity;


use AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testRoles()
    {
        $expectedRoles = ['ROLE_USER'];

        $user = new User();
        $this->assertEquals($expectedRoles, $user->getRoles(), 'Different roles');
    }
}