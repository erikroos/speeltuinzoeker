<?php
require_once('third_party/TwitterAPIExchange.php');

class Twitter {
    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    private static $settings = array(
        'oauth_access_token' => OAUTH_ACCESS_TOKEN,
        'oauth_access_token_secret' => OAUTH_ACCESS_TOKEN_SECRET,
        'consumer_key' => CONSUMER_KEY,
        'consumer_secret' => CONSUMER_SECRET
    );

    /** URL for REST request, see: https://developer.twitter.com/en/docs/api-reference-index **/
    private static $baseUrl = 'https://api.twitter.com/1.1/';

    private static $requestMethod = 'POST';

    public static function tweet($message) {
        /** POST fields required by the URL. See relevant docs as above **/
        $postFields = array(
            "status" => $message
        );
        self::performTwitterRequest($postFields, self::$baseUrl . "statuses/update.json");
    }

    private static function performTwitterRequest($postFields, $url) {
        $twitter = new TwitterAPIExchange(self::$settings);
        try {
            echo $twitter->buildOauth($url, self::$requestMethod)
                ->setPostfields($postFields)
                ->performRequest();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}