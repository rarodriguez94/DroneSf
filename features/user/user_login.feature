@users
Feature: visit the store
  In order to view my store
  As a visitor
  I need to be able to see some content


  Scenario: Visit page
    Given I am on the store homepage
    Then I should see "homepage"