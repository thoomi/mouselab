'use strict';

describe('Controller: DecisionStrategyCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var DecisionStrategyCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    DecisionStrategyCtrl = $controller('DecisionStrategyCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
