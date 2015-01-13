'use strict';

describe('Controller: TaskdescriptionCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var TaskdescriptionCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    TaskdescriptionCtrl = $controller('TaskdescriptionCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
