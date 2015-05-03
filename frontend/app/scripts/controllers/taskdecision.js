'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskdecisionCtrl
 * @description
 * # TaskdecisionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskdecisionCtrl', function ($scope, $interval, $location, dataService, configData, randomizer) {
        if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

        // Define the washing powder rating test cases
        $scope.ratingTestCases = configData.getRatingTestCases();
        $scope.washingPowders  = configData.getWashingPowders(dataService.getCurrentTask());
        $scope.currentRound    = dataService.getCurrentRound();
        $scope.timerRunning    = true;
        $scope.availableTime   = configData.getAvailableTime(dataService.getCurrentTask());


        // Shuffle the washing powders
        randomizer.shuffleArray($scope.washingPowders);

        // Timer Settings
        var chosenOptionRank = 0;
        var chosenOptionPosition = 0;

        function saveExperiment(timeToDecision) {
          dataService.saveExperiment(chosenOptionRank, timeToDecision, chosenOptionPosition, function(error){
            if (!error)
            {
              $location.path('taskquestions');
            }
            else
            {
              console.log(error);
              // TODO: Handle error properly
            }
          });
        }


        var intervalId = $interval(function() {
          if ($scope.availableTime <= 0)
          {
            $interval.cancel(intervalId);
            $scope.timerRunning = false;
            saveExperiment(0);
            return;
          }

          $scope.availableTime -= 10;

        }, 10);


        $scope.$on('$destroy', function() {
          // Make sure that the interval is destroyed too
          $interval.cancel(intervalId);
        });



        $scope.itemSelected = function (optionRank, optionPosition) {
            chosenOptionRank     = optionRank;
            chosenOptionPosition = optionPosition;

            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
            $interval.cancel(intervalId);
        };

        $scope.$on('timer-stopped', function (event, data) {
          if (!event.targetScope.countdown)
          {
            saveExperiment(data.millis);
          }
        });
  });
