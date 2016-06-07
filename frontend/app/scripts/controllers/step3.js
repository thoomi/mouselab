'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step3Ctrl
 * @description
 * # Step3Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step3Ctrl', function ($scope, $window, $location, dataService) {
      if (!dataService.everythingIsValid()) { $location.path(''); }
      
      $scope.personalCode = '';
      $scope.notWaitingForRequestToFinish = true;
      $scope.isPreviousParticipant = '';

      dataService.startTime();
      dataService.incrementSiteNumber();

      $scope.onFormSubmit = function() {
        if (!$scope.personalCode || !$scope.isPreviousParticipant) 
        {
          return false;
        }
        
        $scope.notWaitingForRequestToFinish = false;

        dataService.initializeParticipant($scope.personalCode, $scope.isPreviousParticipant, function(error) {
          if (!error)
          {
            $location.path('step4');
          }
          else
          {
            console.log(error);
            // TODO: Handle error properly
          }
        });
      };
  });
