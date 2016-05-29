'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:Step1Ctrl
 * @description
 * # Step1Ctrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('Step1Ctrl', function (dataService) {
    dataService.clearAllData();
    dataService.incrementSiteNumber();
  });
