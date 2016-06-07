'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step4Ctrl
 * @description
 * # Step4Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step4Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();
  });
