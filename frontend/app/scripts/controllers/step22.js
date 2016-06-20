'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step22Ctrl
 * @description
 * # Step22Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step22Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

        $scope.cd  = false;
        $scope.participantEmail = '';
        $scope.participateInOther = 1;
        $scope.comments = '';
        $scope.notWaitingForRequestToFinish = true;
        $scope.successAlert = true;


      $scope.onFormSubmit = function() {
          if ($scope.participantEmail || $scope.comments)
          {
            $scope.saveAndCloseEnabled = true;
            $scope.notWaitingForRequestToFinish = false;
  
  
            dataService.saveUserData($scope.participantEmail, $scope.participateInOther, $scope.comments, function(error) {
                if (!error)
                {
                    // TODO: Reset data redirect to startpage
                    $scope.successAlert = false;
                    $scope.notWaitingForRequestToFinish = true;
                    $location.path('step23');
                }
                else
                {
                    $scope.notWaitingForRequestToFinish = false;
                    $scope.successAlert = false;
                    $location.path('step23');
                }
            });
          }
          else
          {
            $location.path('step23');
          }
          
      };
  });
