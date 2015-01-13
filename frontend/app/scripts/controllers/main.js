'use strict';

/**
 * @ngdoc function
 * @name mouselabApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the mouselabApp
 */
angular.module('mouselabApp')
  .controller('MainCtrl', function (dataService) {
        dataService.clearAllData();
  });
