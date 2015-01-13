'use strict';

describe('Controller: DemographicsCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var DemographicsCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    DemographicsCtrl = $controller('DemographicsCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
