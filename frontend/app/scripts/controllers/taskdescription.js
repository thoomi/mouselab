'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskdescriptionCtrl
 * @description
 * # TaskdescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskdescriptionCtrl', function ($scope, $rootScope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

    $scope.currentRound = dataService.getCurrentRound();
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
