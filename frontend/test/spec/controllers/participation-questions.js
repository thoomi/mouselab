'use strict';

describe('Controller: ParticipationQuestionsCtrl', function () {

  // load the controller's module
  beforeEach(module('mouselabApp'));

  var ParticipationQuestionsCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    ParticipationQuestionsCtrl = $controller('ParticipationQuestionsCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
