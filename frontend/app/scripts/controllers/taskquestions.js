'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskquestionsCtrl
 * @description
 * # TaskquestionsCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskquestionsCtrl', function ($scope, $location, dataService) {
      if (!dataService.everythingIsValid()) { $location.path(''); }

      $scope.allQuestionsAnswered = false;
      $scope.valueQuestion1       = 0;
      $scope.valueQuestion2       = 0;
      $scope.currentRound         = dataService.getCurrentRound();
      $scope.notWaitingForRequestToFinish = true;


      $scope.checkAllSet = function() {
        if ($scope.valueQuestion1 !== 0 && $scope.valueQuestion2 !== 0)
        {
          $scope.allQuestionsAnswered = true;
        }
      };

      $scope.onFormSubmit = function() {
          $scope.notWaitingForRequestToFinish = false;

          dataService.saveStressQuestions($scope.valueQuestion1, $scope.valueQuestion2, function(error) {
              if (!error)
              {
                  $scope.notWaitingForRequestToFinish = true;

                  if (dataService.isLastRound())
                  {
                      $location.path('demographics');
                  }
                  else
                  {
                      dataService.startNextRound();
                      $location.path('taskdecision');
                  }
              }
              else
              {
                  console.log(error);
                  // TODO: Handle error properly
              }
          });
      };
  });
