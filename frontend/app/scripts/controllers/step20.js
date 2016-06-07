'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step11Ctrl
 * @description
 * # Step11Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step20Ctrl', function ($scope, $location, dataService) {
    if (!dataService.everythingIsValid()) { $location.path(''); }

  });
