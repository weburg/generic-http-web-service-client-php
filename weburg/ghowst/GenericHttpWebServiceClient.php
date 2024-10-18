<?php
namespace weburg\ghowst;

require_once "HttpWebServiceInvoker.php";

// Thin wrapper for stubless client
class GenericHttpWebServiceClient
{
    private $baseUrl;
    private $httpWebServiceInvoker;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->httpWebServiceInvoker = new HttpWebServiceInvoker();
    }

    public function __call($name, $arguments)
    {
        return $this->httpWebServiceInvoker->invoke($name, $arguments, $this->baseUrl);
    }
}
?>