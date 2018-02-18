<?php
namespace app\controller_providers;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;

/**
 * Class HistogramControllerProvider
 *
 * Just organises the controller endpoints in one place.
 *
 * @package app\controller_providers
 */
class HistogramControllerProvider implements ControllerProviderInterface
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
        $controllers->get('/{username}', 'app\controllers\HistogramController::execute');
        return $controllers;
    }
}