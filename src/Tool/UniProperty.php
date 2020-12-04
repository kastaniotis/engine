<?php

namespace Iconic\Tool;

use Iconic\Engine\Exception\ObjectParameterRequiredException;

class UniProperty
{
    // https://github.com/symfony/workflow/blob/5.x/MarkingStore/MethodMarkingStore.php
    public static function get(object $subject, string $propertyName)
    {
        //Checking if getPropertyName() exists and call it
        $method = 'get'.ucfirst($propertyName);

        if (method_exists($subject, $method)) {
            return $subject->{$method}();
        }

        if (property_exists($subject, $propertyName)) {
            return $subject->$propertyName;
        }

        throw new ObjectParameterRequiredException('$propertyName');
    }

    public static function set(object $subject, string $propertyName, string $propertyValue)
    {
        //Checking if setPropertyName() exists and call it
        $method = 'set'.ucfirst($propertyName);

        if (method_exists($subject, $method)) {
            $subject->{$method}($propertyValue);

            return;
        }

        if (property_exists($subject, $propertyName)) {
            $subject->$propertyName = $propertyValue;

            return;
        }

        $class = get_class($subject);
        throw new \Exception("The property '$propertyName' does not exist on '$class'.");
    }

    public static function check(object $object, string $propertyName)
    {
        $method = 'get'.ucfirst($propertyName);

        return property_exists($object, $propertyName) || method_exists($object, $method);
    }
}
