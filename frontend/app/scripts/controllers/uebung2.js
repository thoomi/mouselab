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


    $scope.itemSelected = function () {
        $scope.$broadcast('timer-stop');
        $scope.timerRunning = false;
        //$location.path('taskdescription');
    };

    $scope.timerFinished = function () {
      console.log('Timer finished');
    };

    $scope.$on('timer-stopped', function (event, data) {
      if (!event.targetScope.countdown)
      {
        console.log(data.millis);
      }
    });
  });
