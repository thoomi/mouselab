'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:ThanksCtrl
 * @description
 * # ThanksCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step19Ctrl', function ($scope, $location, dataService) {
        //if (!dataService.everythingIsValid()) { $location.path(''); }

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
                }
                else
                {
                    $scope.notWaitingForRequestToFinish = false;
                    $scope.successAlert = false;
                    console.log(error);
                }
            });
          }
          else
          {
            $location.path('step20');
          }
          
      };
  });
