'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Uebung2DescriptionCtrl
 * @description
 * # Uebung2DescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Uebung2DescriptionCtrl', function ($scope, $location, dataService) {
      if (!dataService.everythingIsValid()) { $location.path(''); }

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
