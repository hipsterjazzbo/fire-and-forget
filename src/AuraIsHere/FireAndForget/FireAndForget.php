<?php namespace HipsterJazzbo\FireAndForget;

use HipsterJazzbo\FireAndForget\Exceptions\InvalidUrlException;
use HipsterJazzbo\FireAndForget\Exceptions\SocketException;
use League\Uri\Schemes\Http as HttpUri;
use RuntimeException;

/**
 * Class FireAndForget
 *
 * @package HipsterJazzbo\FireAndForget
 */
class FireAndForget
{

    /**
     * @var int
     */
    private $connectionTimeout;

    /**
     * @param int $connectionTimeout
     */
    function __construct($connectionTimeout = 5)
    {
        $this->connectionTimeout = $connectionTimeout;
    }

    /**
     * @param string $url
     * @param array  $params
     */
    public function get($url, $params)
    {
        $this->fire('GET', $url, $params);
    }

    /**
     * @param string $url
     * @param array  $params
     */
    public function post($url, $params)
    {
        $this->fire('POST', $url, $params);
    }

    /**
     * @param string $url
     * @param array  $params
     */
    public function put($url, $params)
    {
        $this->fire('PUT', $url, $params);
    }

    /**
     * @param string $url
     * @param array  $params
     */
    public function delete($url, $params)
    {
        $this->fire('DELETE', $url, $params);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     *
     * @throws SocketException
     */
    private function fire($method, $url, $params)
    {
        try {
            $url = HttpUri::createFromString($url);
        } catch (RuntimeException $e) {
            throw new InvalidUrlException($e->getMessage(), $url);
        }

        $scheme = $url->getScheme() === 'https' ? 'ssl://' : '';
        $host   = $scheme . $url->getHost();
        $port   = $url->getPort() ?: $this->getDefaultPort($url->getScheme());

        $request = $this->getRequest($method, $url, $params);
        $socket  = @fsockopen($host, $port, $errno, $errstr, $this->connectionTimeout);

        if (! $socket) {
            throw new SocketException($errstr, $errno);
        }

        fwrite($socket, $request);
        fclose($socket);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function buildQueryString($params)
    {
        return http_build_query($params);
    }

    /**
     * @param string  $method
     * @param HttpUri $url
     * @param array   $params
     *
     * @return string
     */
    private function getRequest($method, $url, $params)
    {
        $queryString = $this->buildQueryString($params);
        $headers     = $this->getHeaders($method, $url, $queryString);
        $body        = $this->getBody($method, $queryString);

        return $headers . "\r\n" . $body;
    }

    /**
     * @param string  $method
     * @param HttpUri $url
     * @param string  $queryString
     *
     * @return string
     */
    private function getHeaders($method, $url, $queryString)
    {
        $path = $method === 'GET' ? $url->getPath() . "?" . $queryString : $url->getPath();

        $headers = $method . " /" . $path . " HTTP/1.1\r\n";
        $headers .= "Host: " . $url->getHost() . "\r\n";
        $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $headers .= "Content-Length: " . strlen($queryString) . "\r\n";
        $headers .= "Connection: Close\r\n";

        return $headers;
    }

    /**
     * @param string $method
     * @param string $queryString
     *
     * @return string
     */
    private function getBody($method, $queryString)
    {
        return $method === 'GET' ? '' : $queryString;
    }

    /**
     * @param $scheme
     *
     * @return int
     */
    private function getDefaultPort($scheme)
    {
        switch ($scheme) {
            case 'https':
                $defaultPort = 443;
                break;

            case 'http':
                $defaultPort = 80;
                break;

            default:
                $defaultPort = 80;
        }

        return $defaultPort;
    }
}