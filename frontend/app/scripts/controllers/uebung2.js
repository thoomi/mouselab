'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Uebung2Ctrl
 * @description
 * # Uebung2Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Uebung2Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

    $scope.ratingTestCases = [
      'Preis je Waschgang in Cent',
      'Schmutzentfernung',
      'Fleckenentfernung',
      'Vergrauung',
      'Farberhaltung',
      'Faserschutz',
      'Umwelteigenschaften'
    ];

    $scope.washingPowders = [
      { name  : 'Waschmittel 1', testCaseRatings : [0.20, 'o',  '+',  '-',  '-',  '+',  '+'],  rank : 3},
      { name  : 'Waschmittel 2', testCaseRatings : [0.31, '+',  '++', 'o',  '+',  '-',  'o'],  rank : 2},
      { name  : 'Waschmittel 3', testCaseRatings : [0.26, '+',  '-',  '+',  'o',  '++', 'o'],  rank : 1},
    ];

    $scope.timerRunning = true;

    var chosenOptionRank = 0;


    function saveExperiment (timeToDecision) {
      dataService.saveTrainingData(2, chosenOptionRank, timeToDecision, function(error) {
        if (!error)
        {
          $location.path('taskdescription');
        }
        else
        {
          console.log(error);
          // TODO: Handle error properly
        }
      });
    }



    $scope.itemSelected = function (optionRank) {
        chosenOptionRank = optionRank;

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
