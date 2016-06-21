'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step1Ctrl
 * @description
 * # Step1Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step1Ctrl', function ($scope, $location, dataService) {
    dataService.clearAllData();
    dataService.incrementSiteNumber();
    
    $scope.reward = '';
    
    $scope.onSubmit = function() {
      if (!$scope.reward) { return; }
      
      dataService.setParticipantReward($scope.reward);
      
      $location.path('step2');
    };
  });
