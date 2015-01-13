'use strict';

describe('Controller: TaskdecisionCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var TaskdecisionCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    TaskdecisionCtrl = $controller('TaskdecisionCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
