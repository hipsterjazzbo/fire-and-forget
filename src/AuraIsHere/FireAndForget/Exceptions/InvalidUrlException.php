<?php namespace AuraIsHere\FireAndForget\Exceptions;

use AuraIsHere\FireAndForget\Url;
use InvalidArgumentException;

class InvalidUrlException extends InvalidArgumentException
{

    /**
     * @var \AuraIsHere\FireAndForget\Url
     */
    private $url;

    function __construct($message, Url $url)
    {
        parent::__construct($message, 500);

        $this->url = $url;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }
}