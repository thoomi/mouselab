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


        $scope.itemSelected = function () {
            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
            //$location.path('uebung2-description');
        };

        $scope.timerFinished = function () {
          console.log('Timer finished');
          $location.path('uebung2-description');
        };

        $scope.$on('timer-stopped', function (event, data) {
          console.log(data.millis);
        });
  });
