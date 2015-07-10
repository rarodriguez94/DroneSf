<?php
/**
 * @author: Raul Rodriguez - raulrodriguez782@gmail.com
 * @created: 7/9/15 - 8:52 PM
 */

namespace AppBundle\Behat;


use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

class DefaultContext extends RawMinkContext implements Context, KernelAwareContext
{

    /**
     * @var string
     */
    protected $applicationName = 'dronesf';

    /**
     * @var KernelInterface
     */
    protected $kernel;
    public function __construct($applicationName = null)
    {

        if (null !== $applicationName) {
            $this->applicationName = $applicationName;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }
    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Generate url.
     *
     * @param string  $route
     * @param array   $parameters
     * @param Boolean $absolute
     *
     * @return string
     */
    protected function generateUrl($route, array $parameters = array(), $absolute = false)
    {
        return $this->locatePath($this->getService('router')->generate($route, $parameters, $absolute));
    }

    /**
     * Generate page url.
     * This method uses simple convention where page argument is prefixed
     * with the application name and used as route name passed to router generate method.
     *
     * @param object|string $page
     * @param array         $parameters
     *
     * @return string
     */
    protected function generatePageUrl($page, array $parameters = array())
    {
        if (is_object($page)) {
            return $this->generateUrl($page, $parameters);
        }
        $route  = str_replace(' ', '_', trim($page));
        $routes = $this->getContainer()->get('router')->getRouteCollection();
        if (null === $routes->get($route)) {
            $route = $this->applicationName.'_'.$route;
        }
        if (null === $routes->get($route)) {
            $route = str_replace($this->applicationName.'_', $this->applicationName.'_backend_', $route);
        }
        $route = str_replace(array_keys($this->actions), array_values($this->actions), $route);
        $route = str_replace(' ', '_', $route);
        return $this->generateUrl($route, $parameters);
    }

}