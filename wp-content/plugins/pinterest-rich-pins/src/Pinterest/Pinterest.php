<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vsourz\Pinterest;

use Vsourz\Pinterest\Auth\PinterestOAuth;
use Vsourz\Pinterest\Utils\CurlBuilder;
use Vsourz\Pinterest\Transport\Request;
use Vsourz\Pinterest\Exceptions\InvalidEndpointException;

/**
 * @property \Vsourz\Pinterest\Endpoints\Boards boards
 * @property \Vsourz\Pinterest\Endpoints\Following following
 * @property \Vsourz\Pinterest\Endpoints\Pins pins
 * @property \Vsourz\Pinterest\Endpoints\Users users
 */
class Pinterest {

    /**
     * Reference to authentication class instance
     *
     * @var Auth\PinterestOAuth
     */
    public $auth;

    /**
     * A reference to the request class which travels
     * through the application
     *
     * @var Transport\Request
     */
    public $request;

    /**
     * A array containing the cached endpoints
     *
     * @var array
     */
    private $cachedEndpoints = [];

    /**
     * Constructor
     *
     * @param  string       $client_id
     * @param  string       $client_secret
     * @param  CurlBuilder  $curlbuilder
     */
    public function __construct($client_id, $client_secret, $curlbuilder = null)
    {
        if ($curlbuilder == null) {
            $curlbuilder = new CurlBuilder();
        }

        // Create new instance of Transport\Request
        $this->request = new Request($curlbuilder);

        // Create and set new instance of the OAuth class
        $this->auth = new PinterestOAuth($client_id, $client_secret, $this->request);
    }

    /**
     * Get an Pinterest API endpoint
     *
     * @access public
     * @param string    $endpoint
     * @return mixed
     * @throws Exceptions\InvalidEndpointException
     */
    public function __get($endpoint)
    {
        $endpoint = strtolower($endpoint);
        $class = "\\Vsourz\\Pinterest\\Endpoints\\" . ucfirst($endpoint);

        // Check if an instance has already been initiated
        if (!isset($this->cachedEndpoints[$endpoint])) {
            // Check endpoint existence
            if (!class_exists($class)) {
                throw new InvalidEndpointException;
            }

            // Create a reflection of the called class and initialize it
            // with a reference to the request class
            $ref = new \ReflectionClass($class);
            $obj = $ref->newInstanceArgs([$this->request, $this]);

            $this->cachedEndpoints[$endpoint] = $obj;
        }

        return $this->cachedEndpoints[$endpoint];
    }

    /**
     * Get rate limit from the headers
     *
     * @access public
     * @return integer
     */
    public function getRateLimit()
    {
        $header = $this->request->getHeaders();
        return (isset($header['X-Ratelimit-Limit']) ? $header['X-Ratelimit-Limit'] : 1000);
    }

    /**
     * Get rate limit remaining from the headers
     *
     * @access public
     * @return mixed
     */
    public function getRateLimitRemaining()
    {
        $header = $this->request->getHeaders();
        return (isset($header['X-Ratelimit-Remaining']) ? $header['X-Ratelimit-Remaining'] : 'unknown');
    }
}
