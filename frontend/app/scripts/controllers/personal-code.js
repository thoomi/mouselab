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

      dataService.startTime();

      $scope.onFormSubmit = function() {
        //Fullscreen.all();
        $scope.notWaitingForRequestToFinish = false;

        dataService.initializeParticipant($scope.personalCode, function(error) {
          if (!error)
          {
            $location.path('uebung1-description');
          }
          else
          {
            console.log(error);
            // TODO: Handle error properly
          }
        });
      };
  });
