'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:StatusbarCtrl
 * @description
 * # StatusbarCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('StatusbarCtrl', function ($scope, $rootScope, dataService) {
    $scope.barWidth    = 0;
    $scope.currentSite = 1;

    $rootScope.$on('siteChange', function() {
      $scope.currentSite++;
      $scope.barWidth += 10;
    });
  });
