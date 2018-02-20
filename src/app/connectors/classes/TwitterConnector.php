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

    /** @var string */
    private $key;

    /** @var $secret */
    private $secret;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get Client
     *
     * Uses TwitterOAuth library, if empty
     * @read more at https://github.com/abraham/twitteroauth
     *
     * @return TwitterOAuth
     */
    public function getClient()
    {
        if(empty($this->client)) {
            return new TwitterOAuth($this->getKey(), $this->getSecret());
        }

        return $this->client;
    }

    /**
     * Set Client
     *
     * @param $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

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
        if(!isset($params['key']) || !isset($params['secret'])) {
            throw new MissingConfigException("Please set the twitter key and secret in the config.");
        }

        $this->setKey($params['key']);
        $this->setSecret($params['secret']);
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
        return $this->groupTweetsByHour($this->getTweetsForLastDay($username));
    }

    /**
     * Group Tweets By Hour
     *
     * @param array $tweets
     * @return array
     */
    private function groupTweetsByHour($tweets = array())
    {
        $output = array();
        foreach ($tweets as $tweet) {
            $created_hour = date('H', strtotime($tweet->created_at));

            if(!isset($output[$created_hour])) {
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
     * @param $username
     * @return array
     * @throws InvalidResponseException
     */
    private function getTweetsForLastDay($username)
    {
        $cut_off_date = date_create(date('Y-m-d H:i:s', strtotime('-1 days')));
        $tweets       = $this->getTweets($username, $cut_off_date);

        if(!count($tweets)) {
            throw new InvalidResponseException("No tweets found for the $username");
        }

        return $tweets;
    }

    /**
     * Recursive Get Tweets for the last 24 hours using max id
     *
     * @read More at https://developer.twitter.com/en/docs/tweets/timelines/guides/working-with-timelines
     *
     * @param string $username Username
     * @param \DateTime $cut_off_date Cut Off Date
     * @param string $max_id Max ID
     * @param int $count
     * @return array
     * @throws InvalidResponseException
     */
    private function getTweets($username, $cut_off_date, $max_id = null, $count = 10)
    {
        $request = array(
            'screen_name' => $username,
            'count'       => $count,
        );
        if($max_id !== null) {
            $request['max_id'] = $max_id;
        }

        // Get the response from the client
        $contents = $this->getClient()->get(self::USER_TIMELINE_ENDPOINT, $request);

        if(isset($contents->errors)) {
            throw new InvalidResponseException("Twitter user timeline failed");
        }

        $tweets = array();
        foreach ($contents as $i => $content) {
            $latest_tweet = date_create(date('Y-m-d H:i:s', strtotime($content->created_at)));

            // That's all folks - The End
            if($latest_tweet <= $cut_off_date) {
                break;
            }

            $max_id   = $content->id;
            $tweets[] = $content;

            // Has more?
            if($i == ($count - 1) && $latest_tweet > $cut_off_date) {
                $tweets = array_merge($tweets, $this->getTweets($username, $cut_off_date, $max_id));
            }
        }

        return $tweets;
    }
}