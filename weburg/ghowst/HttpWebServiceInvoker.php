<?php
namespace weburg\ghowst;

use Error;

class HttpWebServiceInvoker
{
    private static function getEntityName($name, $verb)
    {
        return strtolower(substr($name, strlen($verb), strlen($name)));
    }

    private static function generateQs($arguments)
    {
        return ((count($arguments) > 0 ?  '?' : "") . http_build_query($arguments));
    }

    public function invoke($methodName, $arguments, $baseUrl)
    {
        if (strpos($methodName, "get") === 0) {
            $verb = "get";
            $entity = self::getEntityName($methodName, $verb);
        } else if (strpos($methodName, "createOrReplace") === 0) {
            $verb = "createOrReplace";
            $entity = self::getEntityName($methodName, $verb);
        } else if (strpos($methodName, "create") === 0) {
            $verb = "create";
            $entity = self::getEntityName($methodName, $verb);
        } else if (strpos($methodName, "update") === 0) {
            $verb = "update";
            $entity = self::getEntityName($methodName, $verb);
        } else if (strpos($methodName, "delete") === 0) {
            $verb = "delete";
            $entity = self::getEntityName($methodName, $verb);
        } else {
            $parts = preg_split("/(?=[A-Z])/", lcfirst($methodName));

            $verb = strtolower($parts[0]);
            $entity = self::getEntityName($methodName, $verb);
        }

        echo "Verb: $verb\n";
        echo "Entity: $entity\n";

        switch ($verb) {
            case "get":
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $entity . self::generateQs($arguments));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("accept: application/json"));

                $result = curl_exec($ch);

                if ($result !== false) {
                    return json_decode($result);
                } else {
                    $error = curl_error($ch);

                    throw new Error($error);
                }
            case "create":
                $values = array();
                foreach ($arguments as $object) {
                    foreach ($object as $name => $value) {
                        $values[$name] = $value;
                    }
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $entity);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $values);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("accept: application/json"));

                $result = curl_exec($ch);

                if ($result !== false) {
                    return json_decode($result);
                } else {
                    $error = curl_error($ch);

                    throw new Error($error);
                }
            case "createOrReplace":
                $values = array();
                foreach ($arguments as $object) {
                    foreach ($object as $name => $value) {
                        $values[$name] = $value;
                    }
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $entity);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $values);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("accept: application/json"));

                $result = curl_exec($ch);

                if ($result !== false) {
                    return json_decode($result);
                } else {
                    $error = curl_error($ch);

                    throw new Error($error);
                }
            case "update":
                $values = array();
                foreach ($arguments as $object) {
                    foreach ($object as $name => $value) {
                        $values[$name] = $value;
                    }
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $entity);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $values);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("accept: application/json"));

                $result = curl_exec($ch);

                if ($result !== false) {
                    return;
                } else {
                    $error = curl_error($ch);

                    throw new Error($error);
                }
            case "delete":
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $entity . self::generateQs($arguments));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("accept: application/json"));

                $result = curl_exec($ch);

                if ($result !== false) {
                    return;
                } else {
                    $error = curl_error($ch);

                    throw new Error($error);
                }
            default:
                // POST to a custom verb resource

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/' . $entity . '/' . $verb);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("accept: application/json"));

                $result = curl_exec($ch);

                if ($result !== false) {
                    return;
                } else {
                    $error = curl_error($ch);

                    throw new Error($error);
                }
        }
    }
}
?>