'use strict';

describe('Controller: PersonalCodeCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var PersonalCodeCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    PersonalCodeCtrl = $controller('PersonalCodeCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
