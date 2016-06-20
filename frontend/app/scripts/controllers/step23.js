'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step23Ctrl
 * @description
 * # Step23Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step23Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    $scope.score = dataService.getScore();
    
  });
