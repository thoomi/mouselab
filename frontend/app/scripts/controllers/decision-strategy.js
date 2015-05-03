'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:DecisionStrategyCtrl
 * @description
 * # DecisionStrategyCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('DecisionStrategyCtrl', function ($scope, $rootScope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

    $scope.decisionStrategy = dataService.getParticipantStrategy();
    $scope.participantAttributeValues =dataService.getParticipantAttributeValues();


    $scope.ratingTestCases = [
      'Preis je Waschgang in Cent',
      'Schmutzentfernung',
      'Fleckenentfernung',
      'Vergrauung',
      'Farberhaltung',
      'Faserschutz',
      'Umwelteigenschaften'
    ];

    $scope.washingPowders = [
      { name  : 'Waschmittel 1', testCaseRatings : [0.30, 'o',  '-', 'o', '++', '+', '-'],   rank : 2},
      { name  : 'Waschmittel 2', testCaseRatings : [0.35, '++', 'o', '-', 'o', '++', 'o'],   rank : 1},
      { name  : 'Waschmittel 3', testCaseRatings : [0.40, '+',  '+', '+', '--', '++', '++'], rank : 3}
    ];

    $rootScope.$on('strategyChange', function(event, strategy) {
      $scope.decisionStrategy = strategy;
    });
  });
