<?php
namespace app\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class HelloController
 *
 * /hello/BarackObama -> will respond with Hello BarackObama as text
 *
 * @package App\Controller
 */
class HelloController
{
    /**
     * Hello
     *
     * Functions that returns wth the Hello Test
     *
     * @param Application $app
     * @param string $username
     * @return Response
     */
    public function hello(Application $app, $username)
    {
        return new Response('Hello ' . $app->escape($username));
    }
}