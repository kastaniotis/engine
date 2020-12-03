Feature: Workflow
  In order to force business logic
  As a developer
  I need a workflow engine to define the rules that I need

  Scenario: Allow a single action
    Given an action "view" is allowed
    Then then the action "view" must appear in the list of available actions
    And the application is allowed the "view" action
    And the application cannot apply the "view" action without an object
    And the application is not allowed the "publish" action
    And the application cannot apply "publish" actions

  Scenario: Allow a single action with a defined transition
    Given the action "publish" allows the transition of the parameter "status" from "draft" to "published"
    Then the application is not allowed the "publish" action without an object
    And the application is not allowed the "publish" action without an object that has the parameter "status"
    And the application is not allowed the "publish" action without an object with the "status" "wrong"
    And the application is allowed the "publish" action with an object with the "status" "draft"
    And the application can apply the "publish" action with an object with the "status" "draft"

  Scenario: Allow a single action with a defined transition and a defined gate
    Given the action "publish" allows the transition of the parameter "status" from "draft" to "published" for actors with the "role" "publisher"
    Then the application is not allowed the "publish" action without an actor
    And the application is not allowed the "publish" action without an actor that has the parameter "role"
    And the application is not allowed the "publish" action without an actor with the "role" "publisher"
    And the application is allowed the "publish" action with an actor with the "role" "publisher"
    And the application can apply the "publish" action with an actor with the "role" "publisher"

  Scenario: Allow a single action with a defined gate
    Given the action "publish" is allowed for actors with the "role" "publisher"
    Then the application is not allowed the "publish" action without an actor
    And the application is not allowed the "publish" action without an actor that has the parameter "role"
    And the application is not allowed the "publish" action without an actor with the "role" "publisher"
    And the application is allowed the "publish" action with an actor with the "role" "publisher" and without an object
    And the application cannot apply the "publish" action with an actor with the "role" "publisher" and without an object

    ## TODO: Empty final on transition, to allow only check action on initial value, without applying. Defined on cms -> all view if published

  Scenario: Dummy
    Given test
    Then run