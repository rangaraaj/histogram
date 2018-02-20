<?php

namespace tests\conntectors\classes;

use app\connectors\classes\TwitterConnector;

class TwitterConnectorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  TwitterConnector */
    private $connector;

    const USERNAME = 'test_username';

    public function setUp()
    {
        $this->connector = new TwitterConnector();
    }

    public function testInitThrowsMissingConfigException()
    {
        $params = array();
        $this->expectException('app\connectors\error\MissingConfigException');
        $this->connector->init($params);
    }

    public function testInit()
    {
        $params           = array();
        $params['key']    = "TEST_KEY";
        $params['secret'] = "TEST_SECRET";
        $this->connector->init($params);

        $this->assertEquals('TEST_KEY', $this->connector->getKey());
        $this->assertEquals('TEST_SECRET', $this->connector->getSecret());
    }

    public function testGroupTweetsByHour()
    {
        $tweets = array();
        for ($i=1; $i<=24; $i++) {
            $tweet = new \StdClass;
            $tweet->created_at = date('Y-m-d H:i:s', strtotime( '+'.$i.' hours'));

            $tweets[] = $tweet;
        }

        $grouped = $this->connector->groupTweetsByHour($tweets);
        $this->assertArrayHasKey('00', $grouped);
        $this->assertArrayHasKey('23', $grouped);

        $i = 0;
        foreach ($grouped as $hour => $count) {
            $this->assertEquals($i++, $hour);
            $this->assertEquals(1, $count);
        }

        $this->assertEquals(24, count($grouped));
    }
}