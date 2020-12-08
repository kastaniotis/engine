<?php

use Behat\Behat\Context\Context;
use Iconic\Engine\Engine;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class CmsContext implements Context
{
    private $engine;
    private $publishable;

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
        assertTrue($this->engine->can($action, $object));
    }

    /**
     * @Given /^everyone cannot "([^"]*)" objects with "([^"]*)" "([^"]*)"$/
     */
    public function everyoneCannotSeeNonPublishedObjects($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;

        assertFalse($this->engine->can($action, $object));
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
        assertFalse($this->engine->can($action));
    }

    /**
     * @Given /^users with "([^"]*)" "([^"]*)" can "([^"]*)" objects$/
     */
    public function usersWithCanObjects($parameter, $value, $action)
    {
        $actor = new StdClass();
        $actor->$parameter = $value;
        $this->engine->can($action, null, $actor);
    }

    /**
     * @Given /^users with "([^"]*)" "([^"]*)" cannot "([^"]*)" objects$/
     */
    public function usersWithCannotObjects($parameter, $value, $action)
    {
        $actor = new StdClass();
        $actor->$parameter = $value;
        assertFalse($this->engine->can($action, null, $actor));
    }

    /**
     * @Given /^I have a worflow allowing everyone the publish action$/
     */
    public function iHaveAWorflowAllowingEveryoneThePublishAction()
    {
        $this->engine = new Iconic\Engine\Engine();
        $this->engine->allow('publish')->of('published', false, true);
    }

    /**
     * @Given /^an object with a boolean property published set to false$/
     */
    public function anObjectWithABooleanPropertyPublishedSetToFalse()
    {
        $this->publishable = new class() {
            public bool $published = false;
        };
    }

    /**
     * @When /^I apply the publish command$/
     */
    public function iApplyThePublishCommand()
    {
        $this->engine->apply('publish', $this->publishable);
    }

    /**
     * @Then /^the transition is applied$/
     */
    public function theTransitionIsApplied()
    {
        assertTrue($this->publishable->published);
    }
}
