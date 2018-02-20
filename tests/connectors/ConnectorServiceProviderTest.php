<?php

namespace tests\conntectors;

use app\connectors\ConnectorServiceProvider;

class ConnectorServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryException()
    {
        $className = "ICantExist";
        $this->expectException("Exception");
        ConnectorServiceProvider::factory($className, array());
    }
}