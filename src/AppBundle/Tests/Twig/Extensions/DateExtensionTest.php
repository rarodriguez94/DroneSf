<?php
/**
 * @author: Raul Rodriguez - raulrodriguez782@gmail.com
 * @created: 6/24/15 - 10:02 PM
 */

namespace AppBundle\Tests\Twig\Extensions;


use AppBundle\Twig\Extensions\DateExtension;

class DateExtensionTest extends \PHPUnit_Framework_TestCase
{

    private $twig;

    public function setUp()
    {
        $this->twig = new \Twig_Environment();
    }
    public function testNullDateValueAsEmptyString()
    {
        $dateExtension = new DateExtension();
        $expectedValue = "";
        $value = $dateExtension->optionalDateFilter($this->twig, null);
        $this->assertEquals($expectedValue, $value);
    }

    public function testEmptyDateValueAsEmptyString()
    {
        $dateExtension = new DateExtension();
        $expectedValue = "";
        $value = $dateExtension->optionalDateFilter($this->twig, "");
        $this->assertEquals($expectedValue, $value);
    }

    public function testEmptyDateValuewithFallbackValueAsString()
    {
        $dateExtension = new DateExtension();
        $expectedValue = "N/A";
        $value = $dateExtension->optionalDateFilter($this->twig, "", "N/A");
        $this->assertEquals($expectedValue, $value);
    }

    public function tearDown()
    {
        $this->twig = null;
    }
}