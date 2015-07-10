<?php
/**
 * @author: Raul Rodriguez - raulrodriguez782@gmail.com
 * @created: 6/24/15 - 3:59 PM
 */

namespace AppBundle\Twig\Extensions;

use Twig_Extension;
use Twig_Environment;
use DateTime;
use DateInterval;
use DateTimeInterface;
use DateTimeZone;

class DateExtension extends Twig_Extension
{
    public function getFilters()
    {

        return array(
            new \Twig_SimpleFilter('optional_date', array($this, 'OptionalDateFilter'), array('needs_environment' => true))
        );
    }

    /**
     * Converts a date to the given format if not empty.
     *
     * <pre>
     *   {{ post.published_at|optional_date("m/d/Y") }}
     * </pre>
     *
     * @param Twig_Environment             $env      A Twig_Environment instance
     * @param DateTime|DateInterval|string $date     A date
     * @param string                       $defaultValue A string
     * @param string                       $format   A format
     * @param DateTimeZone|string          $timezone A timezone
     *
     * @return string The formatted date
     */
    public function optionalDateFilter(Twig_Environment $env, $date, $defaultValue = "", $format = null, $timezone = null)
    {
        if (is_null($date) || empty($date)) {
            return (!is_null($defaultValue))?$defaultValue:"";
        }

        if (null === $format) {
            $formats = $env->getExtension('core')->getDateFormat();
            $format = $date instanceof DateInterval ? $formats[1] : $formats[0];
        }

        if ($date instanceof DateInterval) {
            return $date->format($format);
        }

        return $this->dateFromString($env, $date, $timezone)->format($format);
    }

    /**
     * Converts an input to a DateTime instance.
     *
     * <pre>
     *    {% if date(user.created_at) < date('+2days') %}
     *      {# do something #}
     *    {% endif %}
     * </pre>
     *
     * @param Twig_Environment    $env      A Twig_Environment instance
     * @param DateTime|string     $date     A date
     * @param DateTimeZone|string $timezone A timezone
     *
     * @return DateTime A DateTime instance
     */
    function dateFromString(Twig_Environment $env, $date = null, $timezone = null)
    {
        // determine the timezone
        if (!$timezone) {
            $defaultTimezone = $env->getExtension('core')->getTimezone();
        } elseif (!$timezone instanceof DateTimeZone) {
            $defaultTimezone = new DateTimeZone($timezone);
        } else {
            $defaultTimezone = $timezone;
        }

        if ($date instanceof DateTime || $date instanceof DateTimeInterface) {
            $returningDate = new DateTime($date->format('c'));
            if (false !== $timezone) {
                $returningDate->setTimezone($defaultTimezone);
            } else {
                $returningDate->setTimezone($date->getTimezone());
            }

            return $returningDate;
        }

        $asString = (string) $date;
        if (ctype_digit($asString) || (!empty($asString) && '-' === $asString[0] && ctype_digit(substr($asString, 1)))) {
            $date = '@'.$date;
        }

        $date = new DateTime($date, $defaultTimezone);
        if (false !== $timezone) {
            $date->setTimezone($defaultTimezone);
        }

        return $date;
    }

    public function getName()
    {
        return 'date_extension';
    }
}