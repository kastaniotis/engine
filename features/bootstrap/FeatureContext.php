<?php

use Behat\Behat\Context\Context;
use Iconic\Engine\Exception\WorkflowException;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $engine;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->engine = new Iconic\Engine\Engine();
    }

    /**
     * @Given /^an action "([^"]*)" is allowed$/
     */
    public function anActionActionIsAllowed($action)
    {
        $this->engine->allow($action);
    }

    /**
     * @Then /^then the action "([^"]*)" must appear in the list of available actions$/
     */
    public function thenTheActionActionMustAppearInTheListOfAvailableActions($addedAction)
    {
        assertTrue(key_exists($addedAction, $this->engine->getActions()));
    }

    /**
     * @Given /^the application is allowed the "([^"]*)" action$/
     */
    public function theApplicationCanExecuteTheAction($action)
    {
        assertTrue($this->engine->can($action));
    }

    /**
     * @Given /^the application cannot apply the "([^"]*)" action without an object$/
     */
    public function theApplicationCanApplyTheAction($action)
    {
        try {
            $this->engine->apply($action);
        } catch (WorkflowException $exception) {
            assertEquals("Action '$action' allows transitions only for specific objects. No objects are specified.", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action$/
     */
    public function theApplicationCannotExecuteActions($action)
    {
        assertFalse($this->engine->can($action));
    }

    /**
     * @Given /^the application cannot apply "([^"]*)" actions$/
     */
    public function theApplicationCannotApplyActions($action)
    {
        try {
            $this->engine->apply($action);
        } catch (WorkflowException $exception) {
            assertEquals("Action '$action' is not allowed", $exception->getMessage());
        }
    }

    /**
     * @Given /^the action "([^"]*)" allows the transition of the parameter "([^"]*)" from "([^"]*)" to "([^"]*)"$/
     */
    public function aTransitionOfTheParameterIsAllowedFromTo($action, $parameter, $from, $to)
    {
        $this->engine->allow($action)->of($parameter, $from, $to);
    }

    /**
     * @Then /^the application is not allowed the "([^"]*)" action without an object$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnObject($action)
    {
        assertFalse($this->engine->can($action));
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an object that has the parameter "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnObjectThatHasTheDefinedParameter($action, $parameter)
    {
        $object = new StdClass();

        assertFalse($this->engine->can($action, $object));
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an object with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnObjectWithTheParameter($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;
        assertFalse($this->engine->can($action, $object));
    }

    /**
     * @Given /^the application is allowed the "([^"]*)" action with an object with the "([^"]*)" "([^"]*)"/
     */
    public function theApplicationIsAllowedTheActionWithAnObjectWithTheParameter($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;
        assertTrue($this->engine->can($action, $object));
    }

    /**
     * @Given /^the application can apply the "([^"]*)" action with an object with the "([^"]*)" "([^"]*)"/
     */
    public function theApplicationCanApplyTheActionWithAnObjectWithTheParameter($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;
        $this->engine->apply($action, $object);
    }

    /**
     * @Given /^the action "([^"]*)" allows the transition of the parameter "([^"]*)" from "([^"]*)" to "([^"]*)" for actors with the "([^"]*)" "([^"]*)"$/
     */
    public function theActionAllowsTheTransitionOfTheParameterFromToForActorsWithThe($action, $parameter, $from, $to, $role, $value)
    {
        $this->engine->allow($action)->of($parameter, $from, $to);
        $this->engine->allow($action)->if($role, $value);
    }

    /**
     * @Then /^the application is not allowed the "([^"]*)" action without an actor$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnActor($action)
    {
        $object = new StdClass();
        $object->status = 'draft';
        assertFalse($this->engine->can($action, $object));
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an actor that has the parameter "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnActorThatHasTheParameter($action, $parameter)
    {
        $object = new StdClass();
        $object->status = 'draft';
        $actor = new StdClass();
        assertFalse($this->engine->can($action, $object, $actor));
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an actor with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnActorWithThe($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->status = 'draft';
        $actor = new StdClass();
        $actor->$parameter = 'wrong';
        assertFalse($this->engine->can($action, $object, $actor));
    }

    /**
     * @Given /^the application is allowed the "([^"]*)" action with an actor with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationIsAllowedTheActionWithAnActorWithThe($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->status = 'draft';
        $actor = new StdClass();
        $actor->$parameter = $value;
        assertTrue($this->engine->can($action, $object, $actor));
    }

    /**
     * @Given /^the application can apply the "([^"]*)" action with an actor with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationCanApplyTheActionWithAnActorWithThe($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->status = 'draft';
        $actor = new StdClass();
        $actor->$parameter = $value;
        $this->engine->apply($action, $object, $actor);
    }

    /**
     * @Given /^the action "([^"]*)" is allowed for actors with the "([^"]*)" "([^"]*)"$/
     */
    public function theActionIsAllowedForActorsWithThe($action, $parameter, $value)
    {
        $this->engine->allow($action)->if($parameter, $value);
    }

    /**
     * @Given /^the application is allowed the "([^"]*)" action with an actor with the "([^"]*)" "([^"]*)" and without an object$/
     */
    public function theApplicationIsAllowedTheActionWithAnActorWithTheAndWithoutAnObject($action, $parameter, $value)
    {
        $actor = new StdClass();
        $actor->$parameter = $value;
        assertTrue($this->engine->can($action, null, $actor));
    }

    /**
     * @Given /^the application cannot apply the "([^"]*)" action with an actor with the "([^"]*)" "([^"]*)" and without an object$/
     */
    public function theApplicationCannotApplyTheActionWithAnActorWithTheAndWithoutAnObject($action, $parameter, $value)
    {
        $actor = new StdClass();
        $actor->$parameter = $value;
        try {
            $this->engine->apply($action, null, $actor);
        } catch (WorkflowException $exception) {
            assertEquals("Action '$action' allows transitions only for specific objects. No objects are specified.", $exception->getMessage());
        }

        //TODO: Move this into its own project

        $test = new StdClass();
        $test->status = 'draft';

        dump(\Iconic\Tool\UniProperty::get($test, 'status'));
//        dump(\Iconic\Tool\UniProperty::get($test, 'nothing'));
        \Iconic\Tool\UniProperty::set($test, 'status', 'published');
        dump(\Iconic\Tool\UniProperty::get($test, 'status'));
//        \Iconic\Tool\UniProperty::set($test, 'test', 'published');
    }

    /**
     * @Given /^action "([^"]*)" allows the transition of parameter "([^"]*)" from "([^"]*)" to "([^"]*)" through getters and setters$/
     */
    public function actionAllowsTheTransitionOfParameterFromToThroughGettersAndSetters($action, $parameter, $from, $to)
    {
        $this->engine->allow($action)->of($parameter, $from, $to);
    }

    /**
     * @Then /^the application should be able to read that for action "([^"]*)" the "([^"]*)" is "([^"]*)"$/
     */
    public function theApplicationShouldBeAbleToReadThatTheIs($action, $parameter, $from)
    {
        $post = new \Iconic\Engine\Test\Post($from);

        assertTrue($this->engine->can($action, $post));
    }

    /**
     * @Given /^should be able to change the "([^"]*)" from "([^"]*)" to "([^"]*)" if it applies the action "([^"]*)"$/
     */
    public function shouldBeAbleToChangeTheFromToIfItAppliesTheAction($parameter, $from, $to, $action)
    {
        $post = new \Iconic\Engine\Test\Post($from);
        assertEquals($from, \Iconic\Tool\UniProperty::get($post, $parameter));

        $this->engine->apply($action, $post);
        assertEquals($to, \Iconic\Tool\UniProperty::get($post, $parameter));
    }
}
