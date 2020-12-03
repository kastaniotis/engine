Feature:
  In order to provide some basic cms functionality
  As a developer
  I need to define some basic access rules

  Scenario: Allow everyone to see published documents (same initial and final transition states)
    Given "view" is allowed for everyone on objects with "status" "published"
    Then everyone can "view"  objects with "status" "published"
    And everyone cannot "view" objects with "status" "unpublished"

  Scenario: Allow users with role editor to edit all documents
    Given "edit" is allowed for users with "role" "editor"
    Then not everyone can "edit" objects
    And users with "role" "editor" can "edit" objects
    And users with "role" "viewer" cannot "edit" objects
