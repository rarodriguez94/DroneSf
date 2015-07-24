<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/index", name="app_homepage")
     */
    public function indexAction()
    {
        $env = $this->container->get( 'kernel' )->getEnvironment();

        if ($env == 'test') {
            throw $this->createNotFoundException("Loading Issues");
        }

        return $this->render('default/index.html.twig');
    }
}
