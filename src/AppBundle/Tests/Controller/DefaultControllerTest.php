<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $server = (isset($_SERVER['CI'])? 'localhost:8000': 'dronesf.lo:80');

        $client = static::createClient([], [
            'HTTP_HOST' => $server,
        ]);

        $crawler = $client->request('GET', '/index');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Homepage")')->count() > 0);
    }
}
