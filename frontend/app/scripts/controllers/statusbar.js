'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:StatusbarCtrl
 * @description
 * # StatusbarCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('StatusbarCtrl', function ($scope, $rootScope, $location, configData) {
    $scope.maxNumberOfSites = 30;

    $scope.currentSite = 0;
    $scope.barWidth    = 0;

    $scope.isTestEnv = configData.getExperimentLocation() === 'T';


    $rootScope.$on('siteChange', function() {
      $scope.currentSite++;
      $scope.barWidth = Math.round(100 / $scope.maxNumberOfSites * $scope.currentSite);
    });

    $rootScope.$on('resetPageCount', function() {
      $scope.currentSite = 0;
    });
    
    $scope.onChange = function() {
        $location.path('/step' + $scope.currentSite);
    };
  });
