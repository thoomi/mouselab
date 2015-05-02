'use strict';

describe('Controller: StatusbarCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var StatusbarCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    StatusbarCtrl = $controller('StatusbarCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
