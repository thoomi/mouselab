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
            'Vergrauung',
            'Umwelteigenschaften'
        ];

        $scope.washingPowders = [
            { name  : 'Waschmittel 1', testCaseRatings : [0.19, '+',  '-',  '-'],  rank : 2},
            { name  : 'Waschmittel 2', testCaseRatings : [0.23, 'o',  '++', 'o'],  rank : 1},
            { name  : 'Waschmittel 3', testCaseRatings : [0.28, '++', '+',  '--'], rank : 3}
        ];

        $scope.timerRunning = true;


        $scope.itemSelected = function () {
            $scope.$broadcast('timer-stop');
            $scope.timerRunning = false;
            $location.path('uebung2-description');
        };
  });
