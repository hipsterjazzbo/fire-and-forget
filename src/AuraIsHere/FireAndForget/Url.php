<?php namespace AuraIsHere\FireAndForget;

use AuraIsHere\FireAndForget\Exceptions\InvalidUrlException;

/**
 * Class Url
 *
 * @package AuraIsHere\FireAndForget
 */
class Url {

	/**
	 * @var string The URL as provided
	 */
	private $url;

	/**
	 * @var string The URL scheme
	 */
	private $scheme;
	private $defaultScheme = 'http';

	/**
	 * @var string The host
	 */
	private $host;
	/**
	 * @var string The path
	 */
	private $path;
	/**
	 * @var int The port
	 */
	private $port;
	private $defaultPorts = [
		'http'  => 80,
		'https' => 443
	];

	function __construct($url, $strict = true)
	{
		$this->url = $url;

		if ($strict && ! $this->isValid()) throw new InvalidUrlException('URL is invalid', $this);

		$this->parse();
	}

	public function __toString()
	{
		return $this->getFullUrl();
	}

	/**
	 * @return string
	 */
	public function getFullUrl()
	{
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getScheme()
	{
		return $this->scheme;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @return bool
	 */
	public function isValid()
	{
		return (filter_var($this->url, FILTER_VALIDATE_URL) !== false);
	}

	/**
	 * Parses the URL into its component parts
	 */
	private function parse()
	{
		$parts = parse_url($this->url);

		$this->scheme = isset($parts['scheme']) ? $parts['scheme'] : $this->getDefaultScheme();
		$this->host   = $parts['host'];
		$this->path   = $parts['path'];
		$this->port   = isset($parts['port']) ? $parts['port'] : $this->getDefaultPort();
	}

	/**
	 * @return string
	 */
	private function getDefaultScheme()
	{
		return $this->defaultScheme;
	}

	/**
	 * @return int
	 */
	private function getDefaultPort()
	{
		if (! array_key_exists($this->scheme, $this->defaultPorts))
		{
			return $this->defaultPorts[$this->getDefaultScheme()];
		}

		return $this->defaultPorts[$this->scheme];
	}
}