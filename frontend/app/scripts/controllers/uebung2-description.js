'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Uebung2DescriptionCtrl
 * @description
 * # Uebung2DescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Uebung2DescriptionCtrl', function ($scope, $rootScope, $location, dataService) {
      if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

    // Randomly choosen strategy (possible values: lex, eba, eqw, wadd
    $scope.decisionStrategy = dataService.getParticipantStrategy();
    $scope.participantAttributeValues = dataService.getParticipantAttributeValues();


    $scope.ratingTestCases = [
      'Preis je Waschgang in Cent',
      'Schmutzentfernung',
      'Fleckenentfernung',
      'Vergrauung',
      'Farberhaltung',
      'Faserschutz',
      'Umwelteigenschaften'
    ];

    $rootScope.$on('strategyChange', function(event, strategy) {
      $scope.decisionStrategy = strategy;
    });
  });
