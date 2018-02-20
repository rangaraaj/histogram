<?php
namespace app\connectors;

use app\connectors\error\MissingConfigException;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ConnectorServiceProvider
 *
 * Connector Service Provider provides a simple interface to connect many connector
 * to display the histogram functionality for the given user.
 *
 * In order to configure a new connector, simple add the following in the config file
 * connector.name => Name of the connector like facebook, instagram
 * connector.params => any credentials or connector related parameters
 *
 * And create a new class within the classes folder and implement the ConnectorInterface.
 *
 * @package app\connectors
 */
class ConnectorServiceProvider implements ServiceProviderInterface
{
    /** @var ConnectorInterface */
    public $connector;

    /**
     * Register
     *
     * Registers the Connector Service Provider to the Silex Application.
     *
     * @param Container $app
     * @throws MissingConfigException, if connector name or params in not set
     */
    public function register(Container $app)
    {
        $app['connector'] = $app->protect(function () use ($app) {

            if (!isset($app['name']) || !isset($app['params'])) {
                throw new MissingConfigException("Please add name and params for the connector in config.");
            }

            $this->connector = self::factory($app['name'], $app['params']);
            return $this->connector;
        });
    }

    /**
     * Factory
     *
     * Creates the connector class based on the name
     *
     * @param string $name Name of the Connector
     * @param array $params Connector Parameters
     * @return ConnectorInterface
     * @throws \Exception
     */
    public static function factory($name, array $params)
    {
        $name = ucfirst($name);
        $className = '\\' . __NAMESPACE__ . '\\classes\\' . $name . "Connector";

        if (!class_exists($className)) {
            throw new \Exception("$name doesn't exists.");
        }

        /** @var ConnectorInterface $connector */
        $connector = new $className;
        $connector->init($params);

        return $connector;
    }
}