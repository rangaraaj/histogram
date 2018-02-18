<?php
namespace app\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HistogramController
 *
 * /histogram/Ferrari -> will respond with a JSON structure displaying the
 * number of tweets per hour of the day
 *
 * @package app\controllers
 */
class HistogramController
{
    /**
     * Execute
     *
     * This function uses the ConnectorServiceProvider to connect to the given third party connector.
     * And retrieves the number of messages per hour of the day of the given user.
     *
     * @param Request $request
     * @param Application $app
     * @param $username
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function execute(Request $request, Application $app, $username)
    {
        $username = $request->get('username');

        $response = $app['connector']()->getData($username);

        return $app->json($response);
    }
}