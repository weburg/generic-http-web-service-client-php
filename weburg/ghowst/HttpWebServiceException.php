<?php
namespace weburg\ghowst;

use RuntimeException;

class HttpWebServiceException extends RuntimeException {
    private $httpStatus;

    public function __construct($httpStatus, $message) {
        parent::__construct($message);
        $this->httpStatus = $httpStatus;
    }

    public function getHttpStatus() {
        return $this->httpStatus;
    }
}
?>