<?php

use Behat\Behat\Context\Context;
use Iconic\Engine\Engine;
use function PHPUnit\Framework\assertEquals;

class CmsContext implements Context
{
    private $engine;

    public function __construct()
    {
        $this->engine = new Engine();
    }

    /**
     * @Given /^"([^"]*)" is allowed for everyone on objects with "([^"]*)" "([^"]*)"$/
     */
    public function isAllowedForEveryoneOnObjectsWith($action, $parameter, $value)
    {
        $this->engine->allow($action)->of($parameter, $value, $value);
    }

    /**
     * @Then /^everyone can "([^"]*)"  objects with "([^"]*)" "([^"]*)"$/
     */
    public function everyoneCanSeePublishedObjects($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;
        $this->engine->can($action, $object);
    }

    /**
     * @Given /^everyone cannot "([^"]*)" objects with "([^"]*)" "([^"]*)"$/
     */
    public function everyoneCannotSeeNonPublishedObjects($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;
        try {
            $this->engine->can($action, $object);
        }
        catch (Exception $exception){
            assertEquals("The transition '$action' cannot be applied for objects with a '$parameter' '$value'. Expected: '$parameter' is 'published'", $exception->getMessage());
        }
    }

    /**
     * @Given /^"([^"]*)" is allowed for users with "([^"]*)" "([^"]*)"$/
     */
    public function isAllowedForUsersWith($action, $parameter, $value)
    {
        $this->engine->allow($action)->if($parameter, $value);
    }

    /**
     * @Then /^not everyone can "([^"]*)" objects$/
     */
    public function notEveryoneCanObjects($action)
    {
        try {
            $this->engine->can($action);
        }
        catch (Exception $exception){
            assertEquals("Only specific actors are allowed action $action", $exception->getMessage());
        }
    }

    /**
     * @Given /^users with "([^"]*)" "([^"]*)" can "([^"]*)" objects$/
     */
    public function usersWithCanObjects($parameter, $value,$action)
    {
        $actor = new StdClass();
        $actor->$parameter = $value;
        $this->engine->can($action, null, $actor);
    }

    /**
     * @Given /^users with "([^"]*)" "([^"]*)" cannot "([^"]*)" objects$/
     */
    public function usersWithCannotObjects($parameter, $value,$action)
    {
        $actor = new StdClass();
        $actor->$parameter = $value;
        try {
            $this->engine->can($action, null, $actor);
        }
        catch (Exception $exception){
            assertEquals("The action '$action' cannot be applied by '$parameter': '$value'. Expected: 'editor'", $exception->getMessage());
        }
    }
}