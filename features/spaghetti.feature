@spaghetti
Feature: Spaghetti
  As a developer i want cook virtual spaghetti
  by using SWF service

  Scenario: I want start workflow
    Given workflow name as "spaghetti"
    And workflow version as "0.1.0"
    And domain name as "cphp-demo-0.1.0"
    When I send startWorkflowExecution request to SWF
    Then response should be instance of "Aws\Result"
