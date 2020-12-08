Feature:
  In order to provide some basic cms functionality
  As a developer
  I need to define some basic access rules

#  Scenario: Allow everyone to see published documents (same initial and final transition states)
#    Given "view" is allowed for everyone on objects with "status" "published"
#    Then everyone can "view"  objects with "status" "published"
#    And everyone cannot "view" objects with "status" "unpublished"
#
#  Scenario: Allow users with role editor to edit all documents
#    Given "edit" is allowed for users with "role" "editor"
#    Then not everyone can "edit" objects
#    And users with "role" "editor" can "edit" objects
#    And users with "role" "viewer" cannot "edit" objects

  Scenario: Apply transitions to boolean properties
    Given I have a worflow allowing everyone the publish action
    And an object with a boolean property published set to false
    When I apply the publish command
    Then the transition is applied