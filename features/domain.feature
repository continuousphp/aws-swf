@domain
Feature: Domains
  As a developer of workflow stack
  I should be able to list my SWF domain

  Scenario: Send request to AWS SWF API to list my domains
    Given domain name as "cphp-demo-0.1.0"
    And are REGISTERED
    When I send describeDomain request to SWF
    Then response should be instance of "Aws\Result"
