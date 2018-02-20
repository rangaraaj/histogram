<?php

namespace tests\controllers;

use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HelloControllerTest extends WebTestCase
{

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    public function createApplication()
    {
        return require __DIR__ . '/../../../app/app.php';
    }

    public function testDefaultPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/hello/testme');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('body:contains("Hello testme")'));
    }
}