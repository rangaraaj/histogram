<?php

namespace tests\conntectors\classes;

use app\connectors\classes\TwitterConnector;
use app\connectors\error\InvalidResponseException;

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

        $grouped = $this->invokeMethod($this->connector, 'groupTweetsByHour', [$tweets]);
        $this->assertArrayHasKey('00', $grouped);
        $this->assertArrayHasKey('23', $grouped);

        $i = 0;
        foreach ($grouped as $hour => $count) {
            $this->assertEquals($i++, $hour);
            $this->assertEquals(1, $count);
        }

        $this->assertEquals(24, count($grouped));
    }

    public function testGetTweets()
    {
        $content_json_1 = '[
            {"id":101,"created_at":"2017-01-01 09:00:00"},
            {"id":234,"created_at":"2017-01-01 08:00:00"},
            {"id":300,"created_at":"2017-01-01 07:00:00"},
            {"id":411,"created_at":"2017-01-01 06:00:00"},
            {"id":555,"created_at":"2017-01-01 05:00:00"}
        ]';
        $content_json_2 = '[
            {"id":666,"created_at":"2017-01-01 04:00:00"},
            {"id":786,"created_at":"2017-01-01 03:00:00"},
            {"id":808,"created_at":"2017-01-01 02:00:00"},
            {"id":911,"created_at":"2017-01-01 01:00:00"}
        ]';
        $mock = $this->getMockBuilder('SomeClient')
            ->setMethods(array('get'))
            ->getMock();

        $mock->expects($this->exactly(2))
            ->method('get')
            ->with(TwitterConnector::USER_TIMELINE_ENDPOINT)
            ->will($this->onConsecutiveCalls(
                $this->returnValue(json_decode($content_json_1)),
                $this->returnValue(json_decode($content_json_2))
            ));

        $this->connector->setClient($mock);
        $cut_off_date = date_create(date('Y-m-d H:i:s', strtotime('2017-01-01 02:59:59')));
        $tweets = $this->invokeMethod($this->connector, 'getTweets', [self::USERNAME, $cut_off_date, null, 5]);

        $this->assertEquals(7, count($tweets));
        $tweets = json_decode(json_encode($tweets), true);
        $ids = array_map(function($value) {
            return $value['id'];
        }, $tweets);
        $this->assertEquals(array(101,234,300,411,555,666,786), $ids);
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}