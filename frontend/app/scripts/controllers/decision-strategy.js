'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:DecisionStrategyCtrl
 * @description
 * # DecisionStrategyCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('DecisionStrategyCtrl', function ($scope) {
    // Randomly choosen strategy (possible values: lex, eba, eqw, wadd
    $scope.decisionStrategy = 'wadd';


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

    //$scope.participantAttributeValues = dataService.getParticipantAttributeValues();
    $scope.participantAttributeValues = [5, 4, 2, 1, 3, 3, 4];


  });
