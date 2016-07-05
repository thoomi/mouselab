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
    
    
    $scope.onSubmit = function() {
      
      dataService.setParticipantReward(1);
      
      $location.path('step2');
    };
  });
