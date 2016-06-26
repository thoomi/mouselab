'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step20Ctrl
 * @description
 * # Step20Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step20Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
        dataService.incrementSiteNumber();

        $scope.allQuestionsAnswered = false;
        $scope.notWaitingForRequestToFinish = true;
        
        
        $scope.metaQuestion1 = 0;
        $scope.metaQuestion2 = 0;
        $scope.metaQuestion3 = 0;
        $scope.metaQuestion4 = 0;
        $scope.metaQuestion5 = 0;
        

        $scope.checkAllSet = function() {
            if ($scope.metaQuestion1 && $scope.metaQuestion2 && $scope.metaQuestion3 && $scope.metaQuestion4 && $scope.metaQuestion5)
            {
                $scope.allQuestionsAnswered = true;
            }
        };

        $scope.onFormSubmit = function() {
            if (!$scope.allQuestionsAnswered) { return; } 
            
            $scope.notWaitingForRequestToFinish = false;
             
            // Save all Answers into an array and calculate the sum
            var answerValues = [$scope.metaQuestion1, $scope.metaQuestion2, $scope.metaQuestion3, $scope.metaQuestion4, $scope.metaQuestion5];
            

            dataService.saveMetaQuestions(answerValues, function(error) {
                if (!error)
                {
                    $scope.notWaitingForRequestToFinish = true;
                    $location.path(dataService.getNextQuestionSet());
                }
                else
                {
                    console.log(error);
                    // TODO: Handle error properly
                }
            });
        };
  });
