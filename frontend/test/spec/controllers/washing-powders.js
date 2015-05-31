'use strict';

describe('Controller: WashingPowdersCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var WashingPowdersCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    WashingPowdersCtrl = $controller('WashingPowdersCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
