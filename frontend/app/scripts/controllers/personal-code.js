'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:PersonalCodeCtrl
 * @description
 * # PersonalCodeCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('PersonalCodeCtrl', function ($scope, $window, $location, Fullscreen, dataService) {
      $scope.personalCode = '';
      $scope.notWaitingForRequestToFinish = true;
      $scope.isPreviousParticipant = '';

      dataService.startTime();

      $scope.onFormSubmit = function() {
        $scope.notWaitingForRequestToFinish = false;

        dataService.initializeParticipant($scope.personalCode, $scope.isPreviousParticipant, function(error) {
          if (!error)
          {
            $location.path('attribute-weights');
          }
          else
          {
            console.log(error);
            // TODO: Handle error properly
          }
        });
      };
  });
