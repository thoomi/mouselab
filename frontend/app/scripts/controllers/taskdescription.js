'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskdescriptionCtrl
 * @description
 * # TaskdescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskdescriptionCtrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    $scope.currentRound = dataService.getCurrentRound();

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


    //$scope.participantAttributeValues = dataService.getParticipantAttributeValues();
    $scope.participantAttributeValues = [5, 4, 2, 1, 3, 3, 4];
  });
