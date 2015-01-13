'use strict';

describe('Controller: Uebung2Ctrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var Uebung2Ctrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    Uebung2Ctrl = $controller('Uebung2Ctrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
