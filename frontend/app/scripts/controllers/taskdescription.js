'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:TaskdescriptionCtrl
 * @description
 * # TaskdescriptionCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('TaskdescriptionCtrl', function ($scope, $location, dataService) {
        if (!dataService.everythingIsValid()) { $location.path(''); }

        $scope.currentRound = dataService.getCurrentRound();
  });
