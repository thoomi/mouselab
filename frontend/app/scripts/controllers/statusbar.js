'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:StatusbarCtrl
 * @description
 * # StatusbarCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('StatusbarCtrl', function ($scope, $rootScope, configData) {
    var maxNumberOfSites = 20;

    $scope.currentSite = 0;
    $scope.barWidth    = 100 / maxNumberOfSites;

    $scope.isTestEnv = configData.getExperimentLocation() === 'T';

    $scope.currentStrategy = '';

    $rootScope.$on('siteChange', function() {
      $scope.currentSite++;
      $scope.barWidth = Math.round(100 / maxNumberOfSites * $scope.currentSite);
    });

    $scope.$watch('currentStrategy', function() {
      $rootScope.$broadcast('strategyChange', $scope.currentStrategy);
    });
  });
