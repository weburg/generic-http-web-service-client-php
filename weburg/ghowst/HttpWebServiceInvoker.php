<?php
namespace weburg\ghowst;

require_once "HttpWebServiceException.php";

class HttpWebServiceInvoker
{
    private static function getResourceName($name, $verb)
    {
        return strtolower(substr($name, strlen($verb), strlen($name)));
    }

    private static function generateQs($arguments)
    {
        return ((count($arguments) > 0 ?  '?' : "") . http_build_query($arguments));
    }

    private static function getHeaders($headers) {
        $headersArray = array();
        $headerLines = explode("\r\n", $headers);

        foreach ($headerLines as $headerLine) {
            $matches = explode(':', $headerLine, 2);
            if (count($matches) == 2) {
                $headersArray[trim($matches[0])] = trim($matches[1]);
            }
        }

        return $headersArray;
    }

    private static function executeAndHandle($ch) {
        $result = curl_exec($ch);

        if ($result !== false) {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = self::getHeaders(substr($result, 0, $headerSize));
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($statusCode >= 400 || $statusCode < 200) {
                throw new HttpWebServiceException($statusCode, $headers["x-error-message"]);
            } else if ($statusCode >= 300 && $statusCode < 400) {
                throw new HttpWebServiceException($statusCode, $headers["location"]);
            }

            return json_decode(substr($result, $headerSize));
        } else {
            $error = curl_error($ch);

            throw new HttpWebServiceException(0, $error);
        }
    }

    private static function nameValuesFromArguments($arguments) {
        $processedArguments = array();
        foreach ($arguments as $argument => $value) {
            if (!is_object($value)) {
                $processedArguments[$argument] = $value;
            } else {
                foreach ($value as $property => $propValue) {
                    $processedArguments[$argument . '.' . $property] = $propValue;
                }
            }
        }

        return $processedArguments;
    }

    public function invoke($methodName, $arguments, $baseUrl)
    {
        if (strpos($methodName, "get") === 0) {
            $verb = "get";
            $resource = self::getResourceName($methodName, $verb);
        } else if (strpos($methodName, "createOrReplace") === 0) {
            $verb = "createOrReplace";
            $resource = self::getResourceName($methodName, $verb);
        } else if (strpos($methodName, "create") === 0) {
            $verb = "create";
            $resource = self::getResourceName($methodName, $verb);
        } else if (strpos($methodName, "update") === 0) {
            $verb = "update";
            $resource = self::getResourceName($methodName, $verb);
        } else if (strpos($methodName, "delete") === 0) {
            $verb = "delete";
            $resource = self::getResourceName($methodName, $verb);
        } else {
            $parts = preg_split("/(?=[A-Z])/", lcfirst($methodName));

            $verb = strtolower($parts[0]);
            $resource = self::getResourceName($methodName, $verb);
        }

        error_log("Verb: $verb");
        error_log("Resource: $resource");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("accept: application/json"));

        try {
            switch ($verb) {
                case "get":
                    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $resource . self::generateQs($arguments));

                    return self::executeAndHandle($ch);
                case "create":
                    $values = self::nameValuesFromArguments($arguments);

                    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $resource);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $values);

                    return self::executeAndHandle($ch);
                case "createOrReplace":
                    $values = self::nameValuesFromArguments($arguments);

                    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $resource);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $values);

                    return self::executeAndHandle($ch);
                case "update":
                    $values = self::nameValuesFromArguments($arguments);

                    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $resource);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $values);

                    return self::executeAndHandle($ch);
                case "delete":
                    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $resource . self::generateQs($arguments));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

                    return self::executeAndHandle($ch);
                default:
                    // POST to a custom verb resource

                    $values = self::nameValuesFromArguments($arguments);

                    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $resource . '/' . $verb);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $values);

                    return self::executeAndHandle($ch);
            }
        } catch (HttpWebServiceException $e) {
            unset($ch);
            throw $e;
        } finally {
            unset($ch);
        }
    }
}
?>