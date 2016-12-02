'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step10-1Ctrl
 * @description
 * # Step6Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step10-1Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();
    
    $scope.experimentCondition = dataService.getParticipantCondition();
    $scope.participantGroup    = dataService.getParticipantGroup();
    $scope.currentround        = dataService.getCurrentRound();


    $scope.instructions =['views/partials/instruction1.html', 'views/partials/instruction2.html'];
        $scope.currentInstruction = 0;
        
        $scope.changeInstruction = function(index) {
          $scope.currentInstruction = index;
        };
  });
