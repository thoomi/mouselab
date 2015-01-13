'use strict';

describe('Controller: MaximisingCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var MaximisingCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    MaximisingCtrl = $controller('MaximisingCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
