'use strict';

describe('Controller: AttributeWeightsCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var AttributeWeightsCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    AttributeWeightsCtrl = $controller('AttributeWeightsCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
