'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:ThanksCtrl
 * @description
 * # ThanksCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('ThanksCtrl', function ($scope, $location, dataService) {
        if (!dataService.everythingIsValid()) { $location.path(''); }

        $scope.saveAndCloseEnabled = false;
        $scope.participantEmail = '';
        $scope.participateInOther = 1;
        $scope.comments = '';
        $scope.notWaitingForRequestToFinish = true;
        $scope.successAlert = true;


      $scope.onFormSubmit = function() {
          $scope.saveAndCloseEnabled = true;
          $scope.notWaitingForRequestToFinish = false;

          if ($scope.participantEmail)
          {
              dataService.saveUserData($scope.participantEmail, $scope.participateInOther, $scope.comments, function(error) {
                  if (!error)
                  {
                      // TODO: Reset data redirect to startpage
                      $scope.successAlert = false;
                      $scope.notWaitingForRequestToFinish = true;
                  }
                  else
                  {
                      $scope.notWaitingForRequestToFinish = false;
                      console.log(error);
                      // TODO: Handle error properly
                  }
              });
          }
          else
          {
              $scope.successAlert = false;
              $scope.notWaitingForRequestToFinish = true;
          }
      };
  });
