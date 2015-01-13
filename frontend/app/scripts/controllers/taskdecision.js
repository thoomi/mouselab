'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskdecisionCtrl
 * @description
 * # TaskdecisionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskdecisionCtrl', function ($scope, $location, dataService, configData) {
        if (!dataService.everythingIsValid()) { $location.path(''); }


        // Define the washing powder rating test cases
        $scope.ratingTestCases = configData.getRatingTestCases();
        $scope.washingPowders  = configData.getWashingPowders(dataService.getCurrentTask());
        $scope.currentRound    = dataService.getCurrentRound();
        $scope.timerRunning    = true;


        // Timer Settings
        var chosenOptionRank = 0;

        $scope.itemSelected = function (optionRank) {
            chosenOptionRank = optionRank;

            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
        };


        $scope.$on('timer-stopped', function (event, data) {
            dataService.saveExperiment(chosenOptionRank, data.millis, function(error){
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
        });
  });
