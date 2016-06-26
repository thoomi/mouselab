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
    $scope.score = Math.min(Math.round(dataService.getScore() * 0.0015 * 100) / 100, 5.95);
    
    
    $timeout(function() {
       window.location = 'http://survey-001-l.stephan-kopietz.de';
    }, 120000);
  });
