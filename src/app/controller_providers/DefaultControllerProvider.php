<?php
namespace app\controller_providers;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Response;
use Silex\Api\ControllerProviderInterface;

/**
 * Class DefaultControllerProvider
 *
 * Just organises the controller endpoints in one place.
 *
 * @package app\controller_providers
 */
class DefaultControllerProvider implements ControllerProviderInterface
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function () {
            return new Response('Try /hello/:name');
        });

        return $controllers;
    }
}