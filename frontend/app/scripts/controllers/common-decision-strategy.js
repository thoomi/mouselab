'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:CommonDecisionStrategyCtrl
 * @description
 * # CommonDecisionStrategyCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('CommonDecisionStrategyCtrl', function ($scope, $rootScope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

    $scope.decisionStrategy = dataService.getParticipantStrategy();

    $rootScope.$on('strategyChange', function(event, strategy) {
      $scope.decisionStrategy = strategy;
    });
  });
