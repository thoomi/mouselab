'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step6Ctrl
 * @description
 * # Step6Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step6Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }
    
    dataService.incrementSiteNumber();

  });
