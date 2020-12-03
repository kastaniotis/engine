<?php

use Behat\Behat\Context\Context;
use Iconic\Engine\Exception\WorkflowException;
use function PHPUnit\Framework\assertEquals;
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
     * @Given /^test$/
     */
    public function test()
    {

    }

    /**
     * @Then /^run$/
     */
    public function run()
    {
        //Done
        $engine = new Iconic\Engine\Engine();

        $engine->allow('view'); //Action

        //Pending
        $engine->allow('publish')->of('status', 'draft', 'published');
        $engine->allow('publish')->if('role', 'admin');

        $engine->allow('unpublish')->of('status', 'published', 'unpublished');
        $engine->allow('unpublish')->if('role', 'publisher');

//        dump($engine->getActions());

        $engine->can('view');
//        $engine->can('view2');
//        $engine->can('publish'); //not allowed without an object
//        $engine->can('publish', new StdClass()); //not allowed without the parameter
        $post = new StdClass();
//        $post->status = 'wrong'; //not allowed with a wrong parameter
        $post->status = 'draft';
//        $engine->can('publish',$post); //allowed with object and parameter


        $actor = new StdClass();
//        $actor->role = 'wrong';
        $actor->role = 'admin';
        $engine->can('publish', $post, $actor);

//        dump($post);

        $engine->apply('publish', $post, $actor);

//        dump($post);
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
        $this->engine->can($action);
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


    public function anActionIsAllowed($arg1)
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action$/
     */
    public function theApplicationCannotExecuteActions($action)
    {
        try {
            $this->engine->can($action);
        } catch (WorkflowException $exception) {
            assertEquals("Action '$action' is not allowed", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application cannot apply "([^"]*)" actions$/
     */
    public function theApplicationCannotApplyActions($action)
    {
        try {
            $this->engine->can($action);
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
        try {
            $this->engine->can($action);
        }
        catch (WorkflowException $exception){
            assertEquals("Action '$action' allows transitions only for specific objects. No objects are specified.", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an object that has the parameter "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnObjectThatHasTheDefinedParameter($action, $parameter)
    {
        $object = new StdClass();
        try {
            $this->engine->can($action, $object);
        }
        catch (WorkflowException $exception){
            assertEquals("The transition can only be applied to objects with a defined '$parameter'.", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an object with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnObjectWithTheParameter($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;
        try {
            $this->engine->can($action, $object);
        }
        catch (WorkflowException $exception){
            assertEquals("The transition '$action' cannot be applied for objects with a '$parameter' '$value'. Expected: '$parameter' is 'draft'", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application is allowed the "([^"]*)" action with an object with the "([^"]*)" "([^"]*)"/
     */
    public function theApplicationIsAllowedTheActionWithAnObjectWithTheParameter($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->$parameter = $value;
        $this->engine->can($action, $object);
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
        $object->status = "draft";
        try {
            $this->engine->can($action, $object);
        }
        catch (WorkflowException $exception){
            assertEquals("Only specific actors are allowed action $action", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an actor that has the parameter "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnActorThatHasTheParameter($action, $parameter)
    {
        $object = new StdClass();
        $object->status = "draft";
        $actor = new StdClass();
        try {
            $this->engine->can($action, $object, $actor);
        }
        catch (WorkflowException $exception){
            assertEquals("The action '$action' can only be applied by actors with a defined '$parameter'.", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application is not allowed the "([^"]*)" action without an actor with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationIsNotAllowedTheActionWithoutAnActorWithThe($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->status = "draft";
        $actor = new StdClass();
        $actor->$parameter = "wrong";
        try {
            $this->engine->can($action, $object, $actor);
        }
        catch (WorkflowException $exception){
            assertEquals("The action '$action' cannot be applied by '$parameter': 'wrong'. Expected: '$value'", $exception->getMessage());
        }
    }

    /**
     * @Given /^the application is allowed the "([^"]*)" action with an actor with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationIsAllowedTheActionWithAnActorWithThe($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->status = "draft";
        $actor = new StdClass();
        $actor->$parameter = $value;
        $this->engine->can($action, $object, $actor);
    }

    /**
     * @Given /^the application can apply the "([^"]*)" action with an actor with the "([^"]*)" "([^"]*)"$/
     */
    public function theApplicationCanApplyTheActionWithAnActorWithThe($action, $parameter, $value)
    {
        $object = new StdClass();
        $object->status = "draft";
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
        $this->engine->can($action, null, $actor);
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
        }
        catch (WorkflowException $exception){
            assertEquals("Action '$action' allows transitions only for specific objects. No objects are specified.", $exception->getMessage());
        }
    }
}
