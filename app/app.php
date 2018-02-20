<?php
date_default_timezone_set('UTC');

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/config.php';

$app          = new Application;
$app['debug'] = $config['debug'];

$app->register(new app\connectors\ConnectorServiceProvider, array(
    'name'   => $config['connector.name'],
    'params' => $config['connector.params'],
));


$app->mount('/', new \app\controller_providers\DefaultControllerProvider);
$app->mount('/hello', new \app\controller_providers\HelloControllerProvider);
$app->mount('/histogram', new \app\controller_providers\HistogramControllerProvider);

//$app->error(function (\Exception $exp, $code) {
//    return new Response($exp->getMessage(), $code);
//});

return $app;