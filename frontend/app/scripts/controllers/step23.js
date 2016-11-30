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
    $scope.score = Math.min((Math.round(dataService.getScore() * 0.0013 * 100) / 100), 6.0);


    $timeout(function() {
       window.location = 'http://survey-002.stephan-kopietz.de';
    }, 120000);
  });
