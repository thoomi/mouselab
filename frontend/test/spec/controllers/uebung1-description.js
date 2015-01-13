'use strict';

describe('Controller: Uebung1DescriptionCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var Uebung1DescriptionCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    Uebung1DescriptionCtrl = $controller('Uebung1DescriptionCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
