'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:UebungCtrl
 * @description
 * # UebungCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Uebung1Ctrl', function ($scope, $location, dataService) {
        if (!dataService.everythingIsValid()) { $location.path(''); }

    dataService.incrementSiteNumber();

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
            { name  : 'Waschmittel 1', testCaseRatings : [0.19, '+',  'o',  '+',  '+',  '-',  '-'],  rank : 2},
            { name  : 'Waschmittel 2', testCaseRatings : [0.25, 'o',  '+',  'o', '++',  '+',  'o'],  rank : 1},
        ];

        $scope.timerRunning = true;


        var chosenOptionRank = 0;

        $scope.itemSelected = function (optionRank) {
            chosenOptionRank = optionRank;

            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
        };

        $scope.$on('timer-stopped', function (event, data) {
          dataService.saveTrainingData(1, chosenOptionRank, data.millis, function(error) {
            if (!error)
            {
              $location.path('uebung2-description');
            }
            else
            {
              console.log(error);
              // TODO: Handle error properly
            }
          });
        });
  });
