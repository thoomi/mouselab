'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskdecisionCtrl
 * @description
 * # TaskdecisionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskdecisionCtrl', function ($scope, $location, dataService, configData, randomizer) {
        if (!dataService.everythingIsValid()) { $location.path(''); }


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

        $scope.itemSelected = function (optionRank, optionPosition) {
            chosenOptionRank     = optionRank;
            chosenOptionPosition = optionPosition;

            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
        };

        $scope.timerFinished = function () {
          saveExperiment(0);
        };

        $scope.$on('timer-stopped', function (event, data) {
          if (!event.targetScope.countdown)
          {
            saveExperiment(data.millis);
          }
        });
  });
