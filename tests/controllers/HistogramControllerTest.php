<?php

namespace tests\controllers;

use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HistogramControllerTest extends WebTestCase
{

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    public function createApplication()
    {
        return require __DIR__ . '/../../app/app.php';
    }

    public function testHistogramPage()
    {
        $client = $this->createClient();
        $client->request('GET', '/histogram/we_dont_accept_!');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}