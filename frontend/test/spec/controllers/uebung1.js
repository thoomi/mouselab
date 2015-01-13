'use strict';

describe('Controller: Uebung1Ctrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var UebungCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UebungCtrl = $controller('Uebung1Ctrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
