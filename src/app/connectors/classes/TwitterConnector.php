<?php
namespace app\connectors\classes;

use Abraham\TwitterOAuth\TwitterOAuth;
use app\connectors\ConnectorInterface;
use app\connectors\error\InvalidResponseException;
use app\connectors\error\MissingConfigException;

/**
 * Class TwitterConnector
 *
 * Twitter Connector connects to the Twitter API. This class retrieves the Tweets for the
 * last day. Once downloaded, it groups the tweet based on the hour of the day.
 *
 * @package app\connectors\classes
 */
class TwitterConnector implements ConnectorInterface
{
    const USER_TIMELINE_ENDPOINT = 'statuses/user_timeline';
    /** @var  TwitterOAuth */
    private $client;

    /**
     * Init
     *
     * Initialises the twitter connector. Sets the client.
     *
     * @param array $params Parameter for the init
     * @throws MissingConfigException, when the key or the secret is missing
     */
    public function init($params = array())
    {
        if (!isset($params['key']) || !isset($params['secret'])) {
            throw new MissingConfigException("Please set the twitter key and secret in the config.");
        }

        $this->client = $this->getClient($params['key'], $params['secret']);
    }

    /**
     * Get Client
     *
     * Uses TwitterOAuth library.
     * @read more at https://github.com/abraham/twitteroauth
     * @param $key string Twitter Consumer Key
     * @param $secret string Twitter Consumer Secret
     * @param null $token string Token
     * @param null $token_secret string Token Secret
     * @return TwitterOAuth
     */
    private function getClient($key, $secret, $token = null, $token_secret = null)
    {
        return new TwitterOAuth($key, $secret, $token, $token_secret);
    }

    /**
     * Get Data
     *
     * Gets the number of tweets per hour of the day
     *
     * @param $username string
     * @return array
     * @throws InvalidResponseException
     */
    public function getData($username)
    {
        $tweets = $this->getTweetsForLastDay($username);

        if (!count($tweets)) {
            throw new InvalidResponseException("No tweets found for the $username");
        }

        $output = array();
        foreach ($tweets as $tweet) {
            $created_hour = date('H', strtotime($tweet->created_at));

            if (!isset($output[$created_hour] )) {
                $output[$created_hour] = 0;
            }
            $output[$created_hour] += 1;
        }

        ksort($output);
        return $output;
    }

    /**
     * Gets the all the tweets for the last 24 hours.
     *
     * @todo implement this
     * @read More at https://developer.twitter.com/en/docs/tweets/timelines/guides/working-with-timelines
     * @param $username string
     * @return array|object
     */
    private function getTweetsForLastDay($username)
    {
        $contents = $this->client->get(self::USER_TIMELINE_ENDPOINT, array(
            'screen_name' => $username,
            'count'       => 10,
        ));

        return $contents;
    }
}