@spaghetti
Feature: Spaghetti
  As a developer i want cook virtual spaghetti
  by using SWF service

  Background:
    Given workflow name as "spaghetti"
    And workflow version as "0.1.0"
    And domain name as "cphp-demo-0.1.0"

  Scenario: I want start workflow
    When I send startWorkflowExecution request to SWF
    Then response should be instance of "Aws\Result"

  Scenario: I want poll workflow decision
    When I call service pollWorkflow
    Then response should be instance of "Continuous\Demo\Swf\Spaghetti\SpaghettiWorkflow"
