'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Uebung1DescriptionCtrl
 * @description
 * # Uebung1DescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Uebung1DescriptionCtrl', function ($scope, $rootScope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

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
