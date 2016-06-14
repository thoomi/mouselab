'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step8Ctrl
 * @description
 * # Step8Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step8Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    dataService.incrementSiteNumber();

    $scope.trainingData = dataService.getTrainingData();
  });
