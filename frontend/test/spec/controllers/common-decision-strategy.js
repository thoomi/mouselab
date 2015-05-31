'use strict';

describe('Controller: CommonDecisionStrategyCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var CommonDecisionStrategyCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    CommonDecisionStrategyCtrl = $controller('CommonDecisionStrategyCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
