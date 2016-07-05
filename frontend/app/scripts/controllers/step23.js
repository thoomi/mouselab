'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step23Ctrl
 * @description
 * # Step23Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step23Ctrl', function ($scope, $location, $timeout, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    $scope.reward = dataService.getParticipantReward();
    $scope.score = (100 / 4030) * Math.min(dataService.getScore(), 4030);
    
    
    $timeout(function() {
       window.location = 'http://survey-001-t.stephan-kopietz.de';
    }, 120000);
  });
