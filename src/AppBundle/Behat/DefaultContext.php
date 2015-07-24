<?php
/**
 * @author: Raul Rodriguez - raulrodriguez782@gmail.com
 * @created: 7/9/15 - 8:52 PM
 */

namespace AppBundle\Behat;


use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Driver\Selenium2Driver;
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
     * @var string
     */
    protected $screenShotPath = 'build/behat/screenshot';

    /**
     * @var KernelInterface
     */
    protected $kernel;
    public function __construct($applicationName = null, $screenShotPath = null)
    {
        if (null !== $applicationName) {
            $this->applicationName = $applicationName;
        }

        if (null != $screenShotPath) {
            $this->screenShotPath = $screenShotPath;
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

    /**
     * Take screen-shot when step fails. Works only with Selenium2Driver.
     *
     * @AfterStep
     * @param AfterStepScope $scope
     */
    public function takeScreenshotAfterFailedStep(AfterStepScope $scope)
    {
        if (99 === $scope->getTestResult()->getResultCode()) {
            $driver = $this->getSession()->getDriver();

            if (! $driver instanceof Selenium2Driver) {
                return;
            }

            if (! is_dir($this->screenShotPath)) {
                mkdir($this->screenShotPath, 0777, true);
            }

            $filename = sprintf(
                '%s_%s_%s.%s',
                $this->getMinkParameter('browser_name'),
                date('Ymd') . '-' . date('His'),
                uniqid('', true),
                'png'
            );

            $this->saveScreenshot($filename, $this->screenShotPath);
        }
    }

}