'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step9Ctrl
 * @description
 * # Step8Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step9Ctrl', function ($scope, $location, dataService, configData) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();
    
    $scope.cueLabels = configData.getCueLabels();
    $scope.cueValues = configData.getCueValues();

  });
 