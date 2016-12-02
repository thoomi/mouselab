'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step11-1Ctrl
 * @description
 * # Step6Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step11-1Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();
    
    $scope.experimentCondition = dataService.getParticipantCondition();
    $scope.participantGroup    = dataService.getParticipantGroup();
    $scope.currentround        = dataService.getCurrentRound();
    
    

  });
